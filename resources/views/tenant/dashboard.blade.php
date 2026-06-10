@extends('tenant.layout')

@section('title', 'Dashboard - KostKu')

@section('content')
<div class="content-header">
    <div class="header-title">
        <h1>Ringkasan Penyewa</h1>
        <p>Pantau booking, pembayaran, dan rekomendasi hunian Anda.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('tenant.search') }}" class="btn btn-primary">
            <i class="bi bi-search"></i> Cari Kos
        </a>
    </div>
</div>

<!-- Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card card-teal">
            <div class="stat-icon">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Booking Aktif</span>
                <span class="stat-value">{{ $stats['active_bookings'] }}</span>
                <span class="stat-delta up"><i class="bi bi-check-circle"></i> Sedang berjalan</span>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card card-amber">
            <div class="stat-icon">
                <i class="bi bi-clock-fill"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Menunggu Bayar</span>
                <span class="stat-value">{{ $stats['pending_payments'] }}</span>
                <span class="stat-delta down"><i class="bi bi-exclamation-circle"></i> Perlu tindakan</span>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card card-green">
            <div class="stat-icon">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Transaksi</span>
                <span class="stat-value">{{ $stats['total_transactions'] }}</span>
                <span class="stat-delta up"><i class="bi bi-shield-check"></i> Tercatat aman</span>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card card-rose">
            <div class="stat-icon">
                <i class="bi bi-heart-fill"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Wishlist</span>
                <span class="stat-value">{{ $stats['wishlist_count'] }}</span>
                <span class="stat-delta down"><i class="bi bi-heart"></i> Kos tersimpan</span>
            </div>
        </div>
    </div>
</div>

<!-- Booking Aktif -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h5 style="margin: 0; font-weight: 700;">Booking Aktif</h5>
                <a href="{{ route('tenant.bookings') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>

            @if($activeBookings->count() > 0)
                <div class="row g-3">
                    @foreach($activeBookings as $booking)
                        <div class="col-md-4">
                            <div style="border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; transition: all 0.3s;">
                                <img src="{{ $booking->room?->property?->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267' }}" alt="{{ $booking->kos_name }}" style="width: 100%; height: 150px; object-fit: cover;">
                                <div style="padding: 1rem;">
                                    <h6 style="margin: 0 0 0.5rem; font-weight: 600;">{{ $booking->kos_name }}</h6>
                                    <p style="margin: 0.25rem 0; font-size: 0.9rem; color: #64748b;">
                                        <i class="bi bi-geo-fill"></i> {{ $booking->location }}
                                    </p>
                                    <p style="margin: 0.25rem 0; font-size: 0.9rem; color: #64748b;">
                                        <i class="bi bi-door-closed"></i> Kamar {{ $booking->room_type }}
                                    </p>
                                    <p style="margin: 0.5rem 0 0; font-size: 0.85rem;">
                                        Durasi: <strong>{{ $booking->duration_months }} bulan</strong>
                                    </p>
                                    <div style="margin-top: 1rem;">
                                        <span class="badge" style="background-color: var(--success); color: white;">Aktif</span>
                                        <a href="{{ route('tenant.booking-detail', $booking) }}" class="btn btn-sm btn-outline-primary float-end">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 2rem; color: #64748b;">
                    <i class="bi bi-inbox" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p>Belum ada booking aktif. <a href="{{ route('tenant.search') }}">Mulai cari kos sekarang!</a></p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Pembayaran Terbaru -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h5 style="margin: 0; font-weight: 700;">Pembayaran Terbaru</h5>
                <a href="{{ route('tenant.payments') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>

            @if($recentPayments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" style="margin: 0;">
                        <thead style="background-color: var(--light);">
                            <tr>
                                <th>Invoice</th>
                                <th>Nama Kos</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                                <tr>
                                    <td><strong>{{ $payment->invoice_number ?? 'N/A' }}</strong></td>
                                    <td>{{ $payment->booking->kos_name ?? $payment->tenant_name }}</td>
                                    <td>Rp {{ number_format($payment->gross_amount ?? $payment->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $payment->payment_method ?? 'Belum dipilih' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payment->payment_status === 'paid' ? 'success' : ($payment->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('tenant.payment-detail', $payment) }}" class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="text-align: center; color: #64748b; margin: 0;">Tidak ada pembayaran terbaru</p>
            @endif
        </div>
    </div>
</div>

<!-- Rekomendasi Kos -->
<div class="row g-3">
    <div class="col-12">
        <div style="margin-bottom: 1.5rem;">
            <h5 style="margin: 0; font-weight: 700;">Rekomendasi Kos Untuk Anda</h5>
            <p style="margin: 0.5rem 0 0; color: #64748b; font-size: 0.9rem;">Temukan kos terbaik yang sesuai dengan kebutuhan Anda</p>
        </div>

        <div class="row g-3">
            @forelse($recommendations as $property)
                <div class="col-md-6 col-lg-4">
                    <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: white; transition: all 0.3s; cursor: pointer;">
                        <div style="position: relative; height: 200px; overflow: hidden;">
                            <img src="{{ $property->image_url }}" alt="{{ $property->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            <form action="{{ route('tenant.wishlist.add', $property) }}" method="POST" style="position: absolute; top: 10px; right: 10px;">
                                @csrf
                                <button class="btn btn-sm btn-light"><i class="bi bi-heart"></i></button>
                            </form>
                        </div>
                        <div style="padding: 1rem;">
                            <h6 style="margin: 0 0 0.5rem; font-weight: 600;">{{ $property->name }}</h6>
                            <p style="margin: 0.25rem 0; font-size: 0.9rem; color: #64748b;">
                                <i class="bi bi-geo-fill"></i> {{ $property->location }}
                            </p>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin: 0.5rem 0; font-size: 0.9rem;">
                                <i class="bi bi-star-fill" style="color: #F59E0B;"></i>
                                <strong>{{ $property->rating ?? 'N/A' }}</strong>
                                <span style="color: #64748b;">({{ $property->review_count }} review)</span>
                            </div>
                            @if($property->rooms()->first())
                                <p style="margin: 0.5rem 0; font-weight: 600; color: var(--primary); font-size: 1rem;">
                                    Mulai dari Rp {{ number_format($property->rooms()->min('price_per_month'), 0, ',', '.') }}/bln
                                </p>
                            @endif
                            <p style="margin: 0.5rem 0 1rem; font-size: 0.85rem; color: #64748b;">
                                @php
                                    $facilities = is_array($property->facilities) ? $property->facilities : [];
                                    $facilityText = implode(', ', array_slice($facilities, 0, 2));
                                @endphp
                                {{ $facilityText ? $facilityText . '...' : 'Lihat fasilitas lengkap' }}
                            </p>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('tenant.property-detail', $property) }}" class="btn btn-sm btn-outline-primary flex-grow-1">Detail</a>
                                <form action="{{ route('tenant.wishlist.add', $property) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button class="btn btn-sm btn-light w-100"><i class="bi bi-heart"></i> Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12" style="text-align: center; padding: 2rem; color: #64748b;">
                    <p>Tidak ada rekomendasi tersedia saat ini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Notifikasi Terbaru -->
@if($notifications->count() > 0)
<div class="row g-3 mt-4">
    <div class="col-12">
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h5 style="margin: 0; font-weight: 700;">Notifikasi Terbaru</h5>
                <a href="{{ route('tenant.notifications') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($notifications as $notification)
                    <div style="padding: 1rem; background-color: var(--light); border-radius: 8px; display: flex; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <div style="flex: 1;">
                            <h6 style="margin: 0; font-weight: 600;">{{ $notification->title }}</h6>
                            <p style="margin: 0.25rem 0 0; font-size: 0.9rem; color: #64748b;">{{ $notification->message }}</p>
                            <small style="color: #94a3b8;">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

@endsection
