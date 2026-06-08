<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        if (!in_array($action, [Payment::STATUS_VERIFIED, Payment::STATUS_REJECTED], true)) {
            return redirect()->route('pembayaran.verifikasi')->with('error', 'Aksi verifikasi tidak valid.');
        }

        $payment->status = $action;
        $payment->notes = $request->input('notes');
        $payment->verified_at = now();
        $payment->save();

        return redirect()->route('pembayaran.verifikasi')->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
