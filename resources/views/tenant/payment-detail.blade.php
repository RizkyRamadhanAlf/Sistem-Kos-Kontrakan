@extends('tenant.layout')

@section('title', 'Detail Pembayaran - KostKu')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('tenant.payments') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-chevron-left"></i> Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <!-- Rincian Pembayaran -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
            <h6 style="margin: 0 0 1.5rem; font-weight: 700;">Rincian Pembayaran</h6>

            <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Nomor Invoice</p>
                <p style="margin: 0.25rem 0 0; font-weight: 600; font-size: 1.1rem;">{{ $payment->invoice_number ?? 'N/A' }}</p>
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 1rem 0; color: #64748b;">Nama Kos</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 600;">{{ $payment->booking->kos_name ?? $payment->tenant_name }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 1rem 0; color: #64748b;">Durasi Sewa</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 600;">{{ $payment->booking->duration_months ?? '-' }} bulan</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 1rem 0; color: #64748b;">Harga per Bulan</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 600;">Rp {{ number_format($payment->booking->price_per_month ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 1rem 0; color: #64748b;">Biaya Admin</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 600;">Rp {{ number_format($payment->booking->admin_fee ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 1rem 0; color: #64748b; font-weight: 700;">Total Pembayaran</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 700; color: var(--primary); font-size: 1.1rem;">Rp {{ number_format($payment->gross_amount ?? $payment->amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Info Pembayaran -->
        @if($payment->payment_status === 'paid')
            <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; border-left: 4px solid var(--success);">
                <h6 style="margin: 0 0 1rem; font-weight: 700;">Pembayaran Berhasil</h6>
                <p style="margin: 0; color: #64748b;">
                    Pembayaran Anda telah diterima dan dikonfirmasi. Terima kasih telah mempercayai KostKu!
                </p>
                <div style="margin-top: 1rem;">
                    <small style="color: #94a3b8;">Tanggal pembayaran: {{ $payment->paid_at?->format('d M Y H:i') }}</small>
                </div>
            </div>
        @elseif($payment->payment_status === 'pending')
            <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; border-left: 4px solid var(--warning);">
                <h6 style="margin: 0 0 1rem; font-weight: 700;">Menunggu Pembayaran</h6>
                <p style="margin: 0; color: #64748b;">
                    Silakan selesaikan pembayaran untuk mengaktifkan booking Anda.
                </p>
                <a href="{{ route('booking.payment.show', $payment->booking) }}" class="btn btn-primary btn-sm mt-2">
                    Lanjutkan Pembayaran
                </a>
            </div>
        @else
            <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; border-left: 4px solid var(--danger);">
                <h6 style="margin: 0 0 1rem; font-weight: 700;">Pembayaran Gagal</h6>
                <p style="margin: 0; color: #64748b;">
                    Pembayaran tidak berhasil diproses. Silakan coba lagi.
                </p>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Status -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
            <h6 style="margin: 0 0 1rem; font-weight: 700;">Status Pembayaran</h6>
            <div style="text-align: center; padding: 1.5rem; background: var(--light); border-radius: 8px;">
                <span class="badge" style="font-size: 0.9rem; padding: 0.5rem 1rem; background-color: {{ $payment->payment_status === 'paid' ? 'var(--success)' : ($payment->payment_status === 'pending' ? 'var(--warning)' : 'var(--danger)') }}; color: white;">
                    {{ ucfirst($payment->payment_status) }}
                </span>
            </div>
        </div>

        <!-- Aksi -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
            <h6 style="margin: 0 0 1rem; font-weight: 700;">Aksi</h6>
            
            @if($payment->payment_status === 'pending')
                <a href="{{ route('booking.payment.show', $payment->booking) }}" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-credit-card-fill"></i> Bayar
                </a>
            @endif

            <a href="{{ route('tenant.invoice.download', $payment) }}" class="btn btn-outline-secondary w-100 mb-2">
                <i class="bi bi-download"></i> Download Invoice
            </a>

            <a href="{{ route('tenant.payments') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-chevron-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
