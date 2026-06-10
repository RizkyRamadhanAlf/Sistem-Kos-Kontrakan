@extends('tenant.layout')

@section('title', 'Booking Saya - KostKu')

@section('content')
<div class="content-header">
    <div class="header-title">
        <h1>Booking Saya</h1>
        <p>Kelola semua booking kos Anda</p>
    </div>
</div>

<!-- Filter -->
<div style="background: white; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
    <div class="btn-group" role="group">
        <a href="{{ route('tenant.bookings', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
        <a href="{{ route('tenant.bookings', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">Menunggu Bayar</a>
        <a href="{{ route('tenant.bookings', ['status' => 'paid']) }}" class="btn btn-sm {{ $status === 'paid' ? 'btn-primary' : 'btn-outline-primary' }}">Aktif</a>
        <a href="{{ route('tenant.bookings', ['status' => 'completed']) }}" class="btn btn-sm {{ $status === 'completed' ? 'btn-primary' : 'btn-outline-primary' }}">Selesai</a>
        <a href="{{ route('tenant.bookings', ['status' => 'cancelled']) }}" class="btn btn-sm {{ $status === 'cancelled' ? 'btn-primary' : 'btn-outline-primary' }}">Dibatalkan</a>
    </div>
</div>

<!-- Booking List -->
<div class="row g-3">
    @forelse($bookings as $booking)
        <div class="col-lg-6">
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; transition: all 0.3s;">
                <img src="{{ $booking->room?->property?->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267' }}" alt="{{ $booking->kos_name }}" style="width: 150px; height: 150px; object-fit: cover;">
                <div style="flex: 1; padding: 1.5rem; display: flex; flex-direction: column;">
                    <h6 style="margin: 0 0 0.5rem; font-weight: 600;">{{ $booking->kos_name }}</h6>
                    <p style="margin: 0.25rem 0; font-size: 0.9rem; color: #64748b;">
                        <i class="bi bi-geo-fill"></i> {{ $booking->location }}
                    </p>
                    <p style="margin: 0.25rem 0; font-size: 0.9rem; color: #64748b;">
                        <i class="bi bi-door-closed"></i> Kamar {{ $booking->room_type }}
                    </p>
                    <div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                        <div>
                            <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Durasi</p>
                            <p style="margin: 0.25rem 0 0; font-weight: 600;">{{ $booking->duration_months }} bulan</p>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Harga</p>
                            <p style="margin: 0.25rem 0 0; font-weight: 600;">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="badge bg-{{ $booking->status === 'paid' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ $booking->status === 'paid' ? 'Aktif' : ($booking->status === 'pending' ? 'Menunggu' : 'Batal') }}
                            </span>
                        </div>
                    </div>
                    <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                        <a href="{{ route('tenant.booking-detail', $booking) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        @if($booking->status === 'pending')
                            <a href="{{ route('booking.payment.show', $booking) }}" class="btn btn-sm btn-primary">Bayar</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12" style="text-align: center; padding: 3rem; background: white; border-radius: 12px;">
            <i class="bi bi-inbox" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
            <h6>Belum Ada Booking</h6>
            <p style="color: #64748b;">Mulai cari dan booking kos yang Anda inginkan</p>
            <a href="{{ route('tenant.search') }}" class="btn btn-primary">Cari Kos Sekarang</a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
{{ $bookings->links('pagination::bootstrap-5') }}
@endsection
