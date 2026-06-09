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
            'status' => Payment::STATUS_PENDING,
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
        $payment = Payment::where('booking_id', $booking->id)->first();

        $clientKey = config('services.midtrans.client_key');
        $isProduction = config('services.midtrans.is_production');

        return view('pembayaran.booking', compact('booking', 'payment', 'clientKey', 'isProduction'));
    }

    /**
     * Create Snap token for a booking
     */
    public function createSnapToken(Request $request, Booking $booking)
    {
        $this->authorize('view', $booking);

        // prevent paying already paid bookings
        if ($booking->status === Booking::STATUS_PAID) {
            return response()->json(['error' => 'Booking sudah dibayar'], 422);
        }

        $existing = Payment::where('booking_id', $booking->id)->first();

        // compute total
        $gross = $booking->total_amount ?? (($booking->price_per_month ?? 0) * ($booking->duration_months ?? 1)) + ($booking->admin_fee ?? 0);

        $invoice = 'INV-'.time().'-'.rand(100, 999);
        $orderId = 'ORDER-'.time().'-'.rand(100, 999);

        // prepare Midtrans
        if (! config('services.midtrans.server_key')) {
            return response()->json(['error' => 'MIDTRANS_SERVER_KEY belum dikonfigurasi.'], 422);
        }

        MidtransConfig::$serverKey = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production');

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $gross,
            ],
            'customer_details' => [
                'first_name' => $booking->tenant_name ?? 'Tamu',
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
                'payment_status' => Payment::STATUS_PENDING,
                'snap_token' => $snapToken,
            ]);
        } else {
            $existing->snap_token = $snapToken;
            $existing->payment_status = Payment::STATUS_PENDING;
            $existing->gross_amount = $gross;
            $existing->order_id = $orderId;
            $existing->invoice_number = $invoice;
            $existing->save();
            $payment = $existing;
        }

        return response()->json(['token' => $snapToken, 'payment' => $payment]);
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
            .config('services.midtrans.server_key')
        );

        abort_unless(hash_equals($expectedSignature, $request->input('signature_key')), 403);

        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');

        $payment = Payment::where('order_id', $orderId)->orWhere('invoice_number', $orderId)->first();
        if (! $payment) {
            return response('OK', 200);
        }

        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $payment->payment_status = Payment::STATUS_PAID;
                $payment->paid_at = now();
                break;
            case 'deny':
            case 'failure':
                $payment->payment_status = Payment::STATUS_FAILED;
                break;
            case 'expire':
                $payment->payment_status = Payment::STATUS_EXPIRED;
                $payment->expired_at = now();
                break;
            default:
                $payment->payment_status = $transactionStatus;
                break;
        }

        $payment->save();

        if ($payment->booking_id && $payment->payment_status === Payment::STATUS_PAID) {
            $booking = Booking::find($payment->booking_id);
            if ($booking) {
                $booking->status = Booking::STATUS_PAID;
                $booking->save();
            }
        }

        return response('OK', 200);
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
