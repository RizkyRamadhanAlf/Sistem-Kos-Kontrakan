<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;
use Midtrans\Transaction as MidtransTransaction;

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

    public function notificationHandler(Request $request)
    {
        $payload = $request->validate([
            'order_id' => ['required', 'string'],
            'transaction_status' => ['required', 'string'],
            'status_code' => ['required', 'string'],
            'gross_amount' => ['required'],
            'signature_key' => ['required', 'string'],
            'payment_type' => ['nullable', 'string'],
            'fraud_status' => ['nullable', 'string'],
        ]);

        $expectedSignature = hash('sha512',
            $payload['order_id']
            .$payload['status_code']
            .$payload['gross_amount']
            .config('midtrans.server_key')
        );

        abort_unless(hash_equals($expectedSignature, $payload['signature_key']), 403, 'Signature Midtrans tidak valid.');

        $payment = Payment::where('order_id', $payload['order_id'])->first();
        if (! $payment) {
            return response()->json(['message' => 'Payment tidak ditemukan.']);
        }

        abort_unless(
            (int) round((float) $payload['gross_amount']) === (int) round((float) ($payment->gross_amount ?? $payment->amount)),
            422,
            'Nominal pembayaran Midtrans tidak sesuai.'
        );

        if ($payment->payment_status !== Payment::STATUS_PAID) {
            $this->applyMidtransStatus(
                $payment,
                $payload['transaction_status'],
                $payload['payment_type'] ?? null,
            );
        }

        return response()->json(['message' => 'Notifikasi Midtrans berhasil diproses.']);
    }

    public function checkStatus(Payment $payment)
    {
        $this->authorize('view', $payment);

        if ($payment->payment_status === Payment::STATUS_PAID) {
            return redirect()
                ->route('tenant.payment-detail', $payment)
                ->with('success', 'Pembayaran berhasil.');
        }

        if (! $payment->order_id) {
            return redirect()
                ->route('tenant.payment-detail', $payment)
                ->with('error', 'Order ID Midtrans belum tersedia.');
        }

        $this->configureMidtrans();

        try {
            $transaction = $this->getMidtransStatus($payment->order_id);
            $grossAmount = (float) ($transaction->gross_amount ?? 0);
            $expectedAmount = (float) ($payment->gross_amount ?? $payment->amount);

            if ((int) round($grossAmount) !== (int) round($expectedAmount)) {
                return redirect()
                    ->route('tenant.payment-detail', $payment)
                    ->with('error', 'Nominal transaksi Midtrans tidak sesuai.');
            }

            $this->applyMidtransStatus(
                $payment,
                $transaction->transaction_status,
                $transaction->payment_type ?? null,
            );
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('tenant.payment-detail', $payment)
                ->with('error', 'Status pembayaran belum dapat diperiksa. Silakan coba lagi.');
        }

        return redirect()
            ->route('tenant.payment-detail', $payment)
            ->with(
                $payment->fresh()->payment_status === Payment::STATUS_PAID ? 'success' : 'status',
                $payment->fresh()->payment_status === Payment::STATUS_PAID
                    ? 'Pembayaran berhasil.'
                    : 'Status pembayaran berhasil diperbarui.'
            );
    }

    protected function applyMidtransStatus(Payment $payment, string $transactionStatus, ?string $paymentType): void
    {
        $status = match ($transactionStatus) {
            'settlement', 'capture' => Payment::STATUS_PAID,
            'pending' => Payment::STATUS_PENDING,
            'cancel' => Payment::STATUS_CANCELLED,
            'expire' => Payment::STATUS_EXPIRED,
            'deny', 'failure' => Payment::STATUS_FAILED,
            default => null,
        };

        if (! $status || $payment->payment_status === Payment::STATUS_PAID) {
            return;
        }

        DB::transaction(function () use ($payment, $status, $paymentType): void {
            $payment->loadMissing('booking.room');
            $payment->payment_status = $status;
            $payment->status = $status;
            $payment->payment_method = $paymentType ?? $payment->payment_method;

            if ($status === Payment::STATUS_PAID) {
                $payment->paid_at = $payment->paid_at ?? now();
                $payment->verified_at = now();
            }

            if ($status === Payment::STATUS_EXPIRED) {
                $payment->expired_at = now();
            }

            $payment->save();

            $booking = $payment->booking;
            if (! $booking) {
                return;
            }

            if ($status === Payment::STATUS_PAID) {
                $booking->update(['status' => Booking::STATUS_PAID]);
                $booking->room?->update(['status' => Room::STATUS_BOOKED]);

                return;
            }

            if ($status === Payment::STATUS_CANCELLED) {
                $booking->update(['status' => Booking::STATUS_CANCELLED]);
            } elseif ($status === Payment::STATUS_EXPIRED) {
                $booking->update(['status' => Booking::STATUS_EXPIRED]);
            }
        });
    }

    protected function configureMidtrans(): void
    {
        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized = config('midtrans.is_sanitized');
        MidtransConfig::$is3ds = config('midtrans.is_3ds');
    }

    protected function getMidtransStatus(string $orderId): object
    {
        return MidtransTransaction::status($orderId);
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
