@extends('tenant.layout')

@section('title', 'Riwayat Transaksi - KostKu')

@section('content')
<div class="content-header">
    <div class="header-title">
        <h1>Riwayat Transaksi</h1>
        <p>Semua transaksi pembayaran Anda</p>
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
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td><strong>{{ $transaction->invoice_number ?? 'N/A' }}</strong></td>
                                <td>{{ $transaction->booking->kos_name ?? $transaction->tenant_name }}</td>
                                <td>{{ $transaction->paid_at?->format('d M Y') ?? $transaction->created_at->format('d M Y') }}</td>
                                <td>Rp {{ number_format($transaction->gross_amount ?? $transaction->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $transaction->payment_method ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $transaction->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($transaction->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('tenant.payment-detail', $transaction) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                        <a href="{{ route('tenant.invoice.download', $transaction) }}" class="btn btn-sm btn-light">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: #64748b;">
                                    Tidak ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{ $transactions->links('pagination::bootstrap-5') }}
@endsection
