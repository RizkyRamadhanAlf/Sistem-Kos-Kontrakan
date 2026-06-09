<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $payment->invoice_number }}</title>
    <style>
        body { color: #1e293b; font-family: Arial, sans-serif; margin: 40px; }
        .header { border-bottom: 3px solid #2563eb; display: flex; justify-content: space-between; padding-bottom: 20px; }
        .brand { color: #2563eb; font-size: 28px; font-weight: bold; }
        table { border-collapse: collapse; margin-top: 30px; width: 100%; }
        td, th { border-bottom: 1px solid #e2e8f0; padding: 12px; text-align: left; }
        .total { color: #2563eb; font-size: 22px; font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div><div class="brand">KostKu</div><small>Invoice pembayaran penyewa</small></div>
        <div><strong>{{ $payment->invoice_number }}</strong><br>{{ $payment->created_at->format('d M Y') }}</div>
    </div>
    <table>
        <tr><th>Nama Kos</th><td>{{ $payment->booking?->kos_name ?? '-' }}</td></tr>
        <tr><th>Kamar</th><td>{{ $payment->booking?->room_type ?? '-' }}</td></tr>
        <tr><th>Metode</th><td>{{ $payment->payment_method ?? 'Midtrans' }}</td></tr>
        <tr><th>Status</th><td>{{ strtoupper($payment->payment_status ?? 'pending') }}</td></tr>
        <tr><th>Total</th><td class="total">Rp {{ number_format($payment->gross_amount ?? $payment->amount, 0, ',', '.') }}</td></tr>
    </table>
</body>
</html>
