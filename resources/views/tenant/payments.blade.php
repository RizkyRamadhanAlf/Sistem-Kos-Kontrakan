@extends('tenant.layout')

@section('title', 'Pembayaran - KostKu')

@section('content')
<div class="content-header">
    <div class="header-title">
        <h1>Pembayaran</h1>
        <p>Kelola pembayaran booking Anda</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
            <div class="table-responsive">
                <table class="table table-hover" style="margin: 0;">
                    <thead style="background-color: var(--light);">
                        <tr>
                            <th>Invoice</th>
                            <th>Nama Kos</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td><strong>{{ $payment->invoice_number ?? 'N/A' }}</strong></td>
                                <td>{{ $payment->booking->kos_name ?? $payment->tenant_name }}</td>
                                <td>Rp {{ number_format($payment->gross_amount ?? $payment->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $payment->payment_method ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $payment->payment_status === 'paid' ? 'success' : ($payment->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('d M Y') }}</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        @if($payment->payment_status === 'pending')
                                            <a href="{{ route('booking.payment.show', $payment->booking) }}" class="btn btn-sm btn-primary">Bayar</a>
                                        @endif
                                        <a href="{{ route('tenant.payment-detail', $payment) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: #64748b;">
                                    Tidak ada pembayaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{ $payments->links('pagination::bootstrap-5') }}
@endsection
