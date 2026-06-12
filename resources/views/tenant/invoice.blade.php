<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        @page { margin: 34px 42px; }
        * { box-sizing: border-box; }
        body { color: #243447; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.55; margin: 0; }
        table { border-collapse: collapse; width: 100%; }
        .header { border-bottom: 3px solid #0f766e; padding-bottom: 18px; }
        .brand { color: #0f766e; font-size: 27px; font-weight: bold; letter-spacing: .5px; }
        .tagline { color: #64748b; font-size: 10px; margin-top: 2px; }
        .invoice-title { color: #0f172a; font-size: 23px; font-weight: bold; letter-spacing: 1px; text-align: right; }
        .invoice-number { color: #0f766e; font-size: 11px; font-weight: bold; text-align: right; }
        .meta { color: #64748b; font-size: 9px; margin-top: 4px; text-align: right; }
        .status { border-radius: 12px; color: #fff; display: inline-block; font-size: 9px; font-weight: bold; padding: 4px 10px; text-transform: uppercase; }
        .status-paid { background: #15803d; }
        .status-pending { background: #d97706; }
        .status-failed, .status-expired { background: #b91c1c; }
        .section { margin-top: 25px; }
        .section-label { color: #0f766e; font-size: 9px; font-weight: bold; letter-spacing: .8px; margin-bottom: 7px; text-transform: uppercase; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 14px; vertical-align: top; }
        .info-title { color: #0f172a; font-size: 12px; font-weight: bold; margin-bottom: 4px; }
        .muted { color: #64748b; }
        .items th { background: #0f766e; color: #fff; font-size: 9px; letter-spacing: .3px; padding: 9px 10px; text-align: left; text-transform: uppercase; }
        .items td { border-bottom: 1px solid #e2e8f0; padding: 11px 10px; vertical-align: top; }
        .right { text-align: right !important; }
        .summary { margin-left: 52%; margin-top: 14px; width: 48%; }
        .summary td { padding: 5px 8px; }
        .summary .grand-total td { background: #ecfdf5; border-bottom: 2px solid #0f766e; border-top: 2px solid #0f766e; color: #0f766e; font-size: 14px; font-weight: bold; padding: 10px 8px; }
        .payment-note { background: #f8fafc; border-left: 3px solid #0f766e; margin-top: 25px; padding: 10px 13px; }
        .footer { border-top: 1px solid #e2e8f0; color: #64748b; font-size: 9px; margin-top: 34px; padding-top: 12px; text-align: center; }
    </style>
</head>
<body>
    @php
        $booking = $payment->booking;
        $status = $payment->payment_status ?? $payment->status ?? 'pending';
        $statusLabels = [
            'paid' => 'Lunas',
            'pending' => 'Menunggu Pembayaran',
            'failed' => 'Gagal',
            'expired' => 'Kedaluwarsa',
        ];
        $subtotal = (float) ($booking?->price_per_month ?? 0) * (int) ($booking?->duration_months ?? 1);
        $adminFee = (float) ($booking?->admin_fee ?? 0);
        $total = (float) ($payment->gross_amount ?? $payment->amount ?? ($subtotal + $adminFee));
    @endphp

    <table class="header">
        <tr>
            <td>
                <div class="brand">KostKu</div>
                <div class="tagline">Hunian nyaman, pemesanan lebih mudah</div>
            </td>
            <td>
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">{{ $invoiceNumber }}</div>
                <div class="meta">Diterbitkan: {{ $payment->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <table class="section">
        <tr>
            <td style="padding-right: 8px; width: 50%;">
                <div class="section-label">Ditagihkan Kepada</div>
                <div class="info-box">
                    <div class="info-title">{{ $payment->tenant_name ?? $payment->user?->name ?? '-' }}</div>
                    <div class="muted">{{ $payment->user?->email ?? '-' }}</div>
                    <div class="muted">{{ $payment->user?->phone ?? '-' }}</div>
                    <div class="muted">{{ $payment->user?->address ?? '-' }}</div>
                </div>
            </td>
            <td style="padding-left: 8px; width: 50%;">
                <div class="section-label">Informasi Pembayaran</div>
                <div class="info-box">
                    <table>
                        <tr><td class="muted">Status</td><td class="right"><span class="status status-{{ $status }}">{{ $statusLabels[$status] ?? ucfirst($status) }}</span></td></tr>
                        <tr><td class="muted">Metode</td><td class="right"><strong>{{ strtoupper(str_replace('_', ' ', $payment->payment_method ?? 'Belum dipilih')) }}</strong></td></tr>
                        <tr><td class="muted">Tanggal Bayar</td><td class="right"><strong>{{ $payment->paid_at?->format('d/m/Y H:i') ?? '-' }}</strong></td></tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-label">Rincian Sewa</div>
        <table class="items">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Periode</th>
                    <th class="right">Harga / Bulan</th>
                    <th class="right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $booking?->kos_name ?? 'Pemesanan Kos' }}</strong><br>
                        <span class="muted">{{ $booking?->room_type ?? 'Tipe kamar tidak tersedia' }} &bull; {{ $booking?->location ?? $booking?->room?->property?->location ?? '-' }}</span>
                    </td>
                    <td>
                        {{ $booking?->duration_months ?? 1 }} bulan<br>
                        <span class="muted">{{ $booking?->check_in_date?->format('d/m/Y') ?? '-' }} s.d. {{ $booking?->check_out_date?->format('d/m/Y') ?? '-' }}</span>
                    </td>
                    <td class="right">Rp {{ number_format($booking?->price_per_month ?? $subtotal, 0, ',', '.') }}</td>
                    <td class="right"><strong>Rp {{ number_format($subtotal ?: ($total - $adminFee), 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <table class="summary">
        <tr><td class="muted">Subtotal</td><td class="right">Rp {{ number_format($subtotal ?: ($total - $adminFee), 0, ',', '.') }}</td></tr>
        <tr><td class="muted">Biaya Admin</td><td class="right">Rp {{ number_format($adminFee, 0, ',', '.') }}</td></tr>
        <tr class="grand-total"><td>Total</td><td class="right">Rp {{ number_format($total, 0, ',', '.') }}</td></tr>
    </table>

    <div class="payment-note">
        <strong>Catatan:</strong>
        Invoice ini dibuat otomatis oleh sistem KostKu dan merupakan bukti rincian transaksi yang sah.
        @if($status !== 'paid')
            Invoice belum berstatus lunas hingga pembayaran berhasil dikonfirmasi.
        @endif
    </div>

    <div class="footer">
        Terima kasih telah menggunakan KostKu.<br>
        Dokumen dibuat otomatis pada {{ now()->format('d/m/Y H:i') }}.
    </div>
</body>
</html>
