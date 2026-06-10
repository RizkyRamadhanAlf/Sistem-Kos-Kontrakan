<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
// Midtrans SDK (optional, requires composer require midtrans/midtrans)
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::orderBy('created_at', 'desc')->get();

        return view('pembayaran.upload', compact('payments'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'amount' => str_replace('.', '', $request->input('amount')),
        ]);

        $validated = $request->validate([
            'tenant_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'payment_date' => 'required|date',
            'receipt' => 'required|image|max:2048',
        ]);

        $receipt = $request->file('receipt');
        $receiptName = Str::slug($validated['tenant_name']).'-'.time().'.'.$receipt->getClientOriginalExtension();
        $receiptFolder = public_path('uploads/receipts');

        if (! is_dir($receiptFolder)) {
            mkdir($receiptFolder, 0755, true);
        }

        $receipt->move($receiptFolder, $receiptName);

        Payment::create([
            'tenant_name' => $validated['tenant_name'],
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'receipt_path' => 'uploads/receipts/'.$receiptName,
            'payment_status' => Payment::STATUS_PENDING,
        ]);

        return redirect()->route('pembayaran.upload')->with('success', 'Bukti pembayaran berhasil diunggah. Silakan tunggu verifikasi.');
    }

    public function verifyIndex()
    {
        $payments = Payment::orderBy('created_at', 'desc')->get();

        return view('pembayaran.verifikasi', compact('payments'));
    }

    public function verify(Request $request, Payment $payment)
    {
        $action = $request->input('action');

        $allowed = [Payment::STATUS_PAID, Payment::STATUS_FAILED, Payment::STATUS_EXPIRED, Payment::STATUS_PENDING];
        if (! in_array($action, $allowed, true)) {
            return redirect()->route('pembayaran.verifikasi')->with('error', 'Aksi verifikasi tidak valid.');
        }

        $payment->payment_status = $action;
        $payment->notes = $request->input('notes');
        if ($action === Payment::STATUS_PAID) {
            $payment->paid_at = now();
        }
        $payment->save();

        // update related booking if exists
        if ($payment->booking_id) {
            $booking = Booking::find($payment->booking_id);
            if ($booking && $action === Payment::STATUS_PAID) {
                $booking->status = Booking::STATUS_PAID;
                $booking->save();
            }
        }

        return redirect()->route('pembayaran.verifikasi')->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    /**
     * Show payment page for a booking
     */
    public function showBookingPayment(Booking $booking)
    {
        $this->authorize('view', $booking);
        $booking->load('room.property');
        $payment = Payment::firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'user_id' => $booking->user_id,
                'invoice_number' => 'INV-'.now()->timestamp.'-'.random_int(100, 999),
                'tenant_name' => $booking->tenant_name,
                'gross_amount' => $booking->total_amount,
                'amount' => $booking->total_amount,
                'payment_date' => now(),
                'payment_status' => Payment::STATUS_PENDING,
                'expired_at' => now()->addDay(),
            ]
        );

        if ($payment->payment_status === Payment::STATUS_PENDING && ! $payment->expired_at) {
            $payment->update(['expired_at' => now()->addDay()]);
        }

        if ($payment->payment_status === Payment::STATUS_PENDING && $payment->expired_at?->isPast()) {
            $payment->update([
                'payment_status' => Payment::STATUS_EXPIRED,
                'expired_at' => now(),
            ]);
            $booking->update(['status' => Booking::STATUS_EXPIRED]);
        }

        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');

        return view('pembayaran.booking', compact('booking', 'payment', 'clientKey', 'isProduction'));
    }

    /**
     * Create Snap token for a booking
     */
    public function createSnapToken(Request $request, Booking $booking)
    {
        $this->authorize('view', $booking);
        $validated = $request->validate([
            'method' => ['nullable', 'string', 'max:100'],
        ]);

        // prevent paying already paid bookings
        if ($booking->status === Booking::STATUS_PAID) {
            return response()->json(['error' => 'Booking sudah dibayar'], 422);
        }

        $existing = Payment::where('booking_id', $booking->id)->first();
        if ($booking->status === Booking::STATUS_CANCELLED) {
            return response()->json(['error' => 'Booking sudah dibatalkan'], 422);
        }
        if ($existing?->expired_at?->isPast() || $booking->status === Booking::STATUS_EXPIRED) {
            return response()->json(['error' => 'Batas waktu pembayaran telah berakhir'], 422);
        }

        // compute total
        $durationMonths = (int) ($booking->duration_months ?? 1);
        $pricePerMonth = (int) ($booking->price_per_month ?? 0);
        $adminFee = (int) ($booking->admin_fee ?? 0);
        $gross = (int) ($booking->total_amount ?? ($pricePerMonth * $durationMonths) + $adminFee);

        $invoice = 'INV-'.time().'-'.rand(100, 999);
        $orderId = 'ORDER-'.time().'-'.rand(100, 999);

        // prepare Midtrans
        if (! config('midtrans.server_key')) {
            return response()->json(['error' => 'MIDTRANS_SERVER_KEY belum dikonfigurasi.'], 422);
        }

        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized = config('midtrans.is_sanitized');
        MidtransConfig::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $gross,
            ],
            'customer_details' => [
                'first_name' => $booking->tenant_name ?? 'Tamu',
            ],
            'item_details' => [
                [
                    'id' => 'KOS-'.$booking->id,
                    'price' => $gross,
                    'quantity' => 1,
                    'name' => 'Pembayaran Kos: '.($booking->kos_name ?? 'Kos'),
                ],
            ],
        ];

        try {
            $snapToken = MidtransSnap::getSnapToken($params);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Gagal membuat snap token: '.$e->getMessage()], 500);
        }

        if (! $existing) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'invoice_number' => $invoice,
                'order_id' => $orderId,
                'tenant_name' => $booking->tenant_name,
                'gross_amount' => $gross,
                'amount' => $gross,
                'payment_date' => now(),
                'payment_status' => Payment::STATUS_PENDING,
                'status' => Payment::STATUS_PENDING,
                'snap_token' => $snapToken,
                'expired_at' => now()->addDay(),
                'payment_method' => $validated['method'] ?? null,
            ]);
        } else {
            $existing->update([
                'snap_token' => $snapToken,
                'payment_status' => Payment::STATUS_PENDING,
                'status' => Payment::STATUS_PENDING,
                'gross_amount' => $gross,
                'amount' => $gross,
                'payment_date' => now(),
                'order_id' => $orderId,
                'invoice_number' => $invoice,
                'payment_method' => $validated['method'] ?? $existing->payment_method,
            ]);
            $payment = $existing;
        }

        return response()->json(['token' => $snapToken, 'payment' => $payment]);
    }

    public function cancelBooking(Booking $booking)
    {
        $this->authorize('update', $booking);

        $booking->update(['status' => Booking::STATUS_CANCELLED]);
        $booking->payment?->update([
            'payment_status' => Payment::STATUS_FAILED,
            'status' => Payment::STATUS_FAILED,
        ]);

        return redirect()->route('tenant.bookings')->with('success', 'Booking berhasil dibatalkan.');
    }

    public function expireBooking(Booking $booking)
    {
        $this->authorize('update', $booking);
        $payment = $booking->payment;

        if ($payment?->payment_status === Payment::STATUS_PENDING && $payment->expired_at?->isPast()) {
            $payment->update([
                'payment_status' => Payment::STATUS_EXPIRED,
                'status' => Payment::STATUS_EXPIRED,
            ]);
            $booking->update(['status' => Booking::STATUS_EXPIRED]);
        }

        return response()->noContent();
    }

    /**
     * Handle Midtrans notification / webhook
     */
    public function webhook(Request $request)
    {
        $request->validate([
            'order_id' => ['required', 'string'],
            'transaction_status' => ['required', 'string'],
            'status_code' => ['required', 'string'],
            'gross_amount' => ['required'],
            'signature_key' => ['required', 'string'],
        ]);

        $expectedSignature = hash('sha512',
            $request->string('order_id')
            .$request->string('status_code')
            .$request->input('gross_amount')
            .config('midtrans.server_key')
        );

        abort_unless(hash_equals($expectedSignature, $request->input('signature_key')), 403);

        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');
        $type = $request->input('payment_type');

        $payment = Payment::where('order_id', $orderId)->orWhere('invoice_number', $orderId)->first();
        if (! $payment) {
            return response('OK', 200);
        }

        if ($transactionStatus === 'capture') {
            if ($type === 'credit_card') {
                if ($request->input('fraud_status') === 'challenge') {
                    $payment->payment_status = 'challenge';
                } else {
                    $payment->payment_status = Payment::STATUS_PAID;
                    $payment->status = Payment::STATUS_PAID;
                }
            }
        } elseif ($transactionStatus === 'settlement') {
            $payment->payment_status = Payment::STATUS_PAID;
            $payment->status = Payment::STATUS_PAID;
        } elseif ($transactionStatus === 'pending') {
            $payment->payment_status = Payment::STATUS_PENDING;
            $payment->status = Payment::STATUS_PENDING;
        } elseif ($transactionStatus === 'deny') {
            $payment->payment_status = Payment::STATUS_FAILED;
            $payment->status = Payment::STATUS_FAILED;
        } elseif ($transactionStatus === 'expire') {
            $payment->payment_status = Payment::STATUS_EXPIRED;
            $payment->status = Payment::STATUS_EXPIRED;
            $payment->expired_at = now();
        } elseif ($transactionStatus === 'cancel') {
            $payment->payment_status = Payment::STATUS_FAILED;
            $payment->status = Payment::STATUS_FAILED;
        }

        $payment->payment_method = $type;
        if ($payment->payment_status === Payment::STATUS_PAID) {
            $payment->paid_at = now();
            $payment->verified_at = now();
        }

        $payment->save();

        if ($payment->booking_id && $payment->payment_status === Payment::STATUS_PAID) {
            $booking = Booking::find($payment->booking_id);
            if ($booking) {
                $booking->status = Booking::STATUS_PAID;
                $booking->save();
            }
        }

        return response()->json(['message' => 'Webhook received']);
    }

    public function success()
    {
        return view('pembayaran.success');
    }

    public function fail()
    {
        return view('pembayaran.fail');
    }
}
