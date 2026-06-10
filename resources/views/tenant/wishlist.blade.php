@extends('tenant.layout')

@section('title', 'Wishlist - KostKu')

@section('content')
<div class="content-header">
    <div class="header-title">
        <h1>Wishlist Saya</h1>
        <p>Kos-kos favorit Anda</p>
    </div>
</div>

<div class="row g-3">
    @forelse($wishlists as $item)
        <div class="col-md-6 col-lg-4">
            <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: white; transition: all 0.3s;">
                <div style="position: relative; height: 200px; overflow: hidden;">
                    <img src="{{ $item->property->image_url }}" alt="{{ $item->property->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    <form action="{{ route('tenant.wishlist.remove', $item->property) }}" method="POST" style="position: absolute; top: 10px; right: 10px;">
                        @csrf
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus dari wishlist?')"><i class="bi bi-heart-fill"></i></button>
                    </form>
                </div>
                <div style="padding: 1rem;">
                    <h6 style="margin: 0 0 0.5rem; font-weight: 600;">{{ $item->property->name }}</h6>
                    <p style="margin: 0.25rem 0; font-size: 0.9rem; color: #64748b;">
                        <i class="bi bi-geo-fill"></i> {{ $item->property->location }}
                    </p>
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin: 0.5rem 0; font-size: 0.9rem;">
                        <i class="bi bi-star-fill" style="color: #F59E0B;"></i>
                        <strong>{{ $item->property->rating ?? 'N/A' }}</strong>
                    </div>
                    @if($item->property->rooms()->first())
                        <p style="margin: 0.5rem 0 1rem; font-weight: 600; color: var(--primary); font-size: 1rem;">
                            Mulai dari Rp {{ number_format($item->property->rooms()->min('price_per_month'), 0, ',', '.') }}/bln
                        </p>
                    @endif
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('tenant.property-detail', $item->property) }}" class="btn btn-sm btn-outline-primary flex-grow-1">Lihat Detail</a>
                        <a href="{{ route('tenant.property-detail', $item->property) }}#kamar" class="btn btn-sm btn-primary flex-grow-1">Booking</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12" style="text-align: center; padding: 3rem; background: white; border-radius: 12px;">
            <i class="bi bi-heart" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
            <h6>Wishlist Kosong</h6>
            <p style="color: #64748b;">Tambahkan kos-kos favorit Anda ke wishlist</p>
            <a href="{{ route('tenant.search') }}" class="btn btn-primary">Cari Kos Sekarang</a>
        </div>
    @endforelse
</div>

{{ $wishlists->links('pagination::bootstrap-5') }}
@endsection
