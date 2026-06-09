<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
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
        $receiptName = Str::slug($validated['tenant_name']) . '-' . time() . '.' . $receipt->getClientOriginalExtension();
        $receiptFolder = public_path('uploads/receipts');

        if (!is_dir($receiptFolder)) {
            mkdir($receiptFolder, 0755, true);
        }

        $receipt->move($receiptFolder, $receiptName);

        Payment::create([
            'tenant_name' => $validated['tenant_name'],
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'receipt_path' => 'uploads/receipts/' . $receiptName,
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
        if (!in_array($action, $allowed, true)) {
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
        $payment = Payment::where('booking_id', $booking->id)->first();
        $clientKey = config('midtrans.client_key');

        return view('pembayaran.booking', compact('booking', 'payment', 'clientKey'));
    }

    /**
     * Create Snap token for a booking
     */
    public function createSnapToken(Request $request, Booking $booking)
    {
        // prevent paying already paid bookings
        if ($booking->status === Booking::STATUS_PAID) {
            return response()->json(['error' => 'Booking sudah dibayar'], 422);
        }

        $existing = Payment::where('booking_id', $booking->id)->first();

        // compute total
        $gross = (int) ($booking->total_amount ?? (($booking->price_per_month ?? 0) * ($booking->duration_months ?? 1)) + ($booking->admin_fee ?? 0));

        $invoice = 'INV-' . time() . '-' . rand(100, 999);
        $orderId = 'ORDER-' . time() . '-' . rand(100, 999);

        // prepare Midtrans
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
                    'id' => 'KOS-' . $booking->id,
                    'price' => $gross,
                    'quantity' => 1,
                    'name' => 'Pembayaran Kos: ' . ($booking->kos_name ?? 'Kos'),
                ]
            ],
        ];

        try {
            $snapToken = MidtransSnap::getSnapToken($params);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Gagal membuat snap token: ' . $e->getMessage()], 500);
        }

        if (!$existing) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'invoice_number' => $invoice,
                'order_id' => $orderId,
                'tenant_name' => $booking->tenant_name,
                'gross_amount' => $gross,
                'amount' => $gross,
                'payment_date' => now(),
                'payment_status' => Payment::STATUS_PENDING,
                'status' => Payment::STATUS_PENDING,
                'snap_token' => $snapToken,
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
            ]);
            $payment = $existing;
        }

        return response()->json(['token' => $snapToken, 'payment' => $payment]);
    }

    /**
     * Handle Midtrans notification / webhook
     */
    public function webhook(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $type = $request->payment_type;

        $payment = Payment::where('order_id', $orderId)->first();
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        if ($transactionStatus == 'capture') {
            if ($type == 'credit_card') {
                if ($request->fraud_status == 'challenge') {
                    $payment->payment_status = 'challenge';
                } else {
                    $payment->payment_status = Payment::STATUS_PAID;
                    $payment->status = Payment::STATUS_PAID;
                }
            }
        } else if ($transactionStatus == 'settlement') {
            $payment->payment_status = Payment::STATUS_PAID;
            $payment->status = Payment::STATUS_PAID;
        } else if ($transactionStatus == 'pending') {
            $payment->payment_status = Payment::STATUS_PENDING;
            $payment->status = Payment::STATUS_PENDING;
        } else if ($transactionStatus == 'deny') {
            $payment->payment_status = Payment::STATUS_FAILED;
            $payment->status = Payment::STATUS_FAILED;
        } else if ($transactionStatus == 'expire') {
            $payment->payment_status = Payment::STATUS_EXPIRED;
            $payment->status = Payment::STATUS_EXPIRED;
            $payment->expired_at = now();
        } else if ($transactionStatus == 'cancel') {
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
