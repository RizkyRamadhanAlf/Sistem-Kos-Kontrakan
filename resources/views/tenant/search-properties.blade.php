@extends('tenant.layout')

@section('title', 'Cari Kos - KostKu')

@section('content')
<div class="content-header">
    <div class="header-title">
        <h1>Cari Kos</h1>
        <p>Temukan kos yang sesuai dengan kebutuhan Anda</p>
    </div>
</div>

<!-- Filter Search -->
<div style="background: white; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
    <form action="{{ route('tenant.search') }}" method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Cari Kos atau Lokasi</label>
            <input type="text" class="form-control" name="search" placeholder="Nama kos atau lokasi" value="{{ request('search') }}">
        </div>
        
        <div class="col-md-3">
            <label class="form-label">Kota</label>
            <select class="form-select" name="city">
                <option value="">Semua Kota</option>
                <option value="Jakarta" {{ request('city') === 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                <option value="Bandung" {{ request('city') === 'Bandung' ? 'selected' : '' }}>Bandung</option>
                <option value="Surabaya" {{ request('city') === 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Harga Min (Rp)</label>
            <input type="number" class="form-control" name="min_price" placeholder="0" value="{{ request('min_price') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label">Harga Max (Rp)</label>
            <input type="number" class="form-control" name="max_price" placeholder="9999999" value="{{ request('max_price') }}">
        </div>

        <div class="col-md-2 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1">
                <i class="bi bi-search"></i> Cari
            </button>
            <a href="{{ route('tenant.search') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Hasil Pencarian -->
<div style="margin-bottom: 1.5rem;">
    <p style="margin: 0; color: #64748b;">
        Menampilkan {{ $properties->total() }} hasil
    </p>
</div>

<div class="row g-3 mb-4">
    @forelse($properties as $property)
        <div class="col-md-6 col-lg-4">
            <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: white; transition: all 0.3s; display: flex; flex-direction: column; height: 100%;">
                <div style="position: relative; height: 200px; overflow: hidden;">
                    <img src="{{ $property->image_url }}" alt="{{ $property->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    <form action="{{ in_array($property->id, $wishlists) ? route('tenant.wishlist.remove', $property) : route('tenant.wishlist.add', $property) }}" method="POST" style="position: absolute; top: 10px; right: 10px;">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ in_array($property->id, $wishlists) ? 'btn-danger' : 'btn-light' }}" style="border-radius: 50%; width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-heart-fill"></i>
                        </button>
                    </form>
                </div>
                <div style="padding: 1rem; flex: 1; display: flex; flex-direction: column;">
                    <h6 style="margin: 0 0 0.5rem; font-weight: 600;">{{ $property->name }}</h6>
                    <p style="margin: 0.25rem 0; font-size: 0.9rem; color: #64748b;">
                        <i class="bi bi-geo-fill"></i> {{ $property->location }}
                    </p>
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin: 0.5rem 0; font-size: 0.9rem;">
                        <i class="bi bi-star-fill" style="color: #F59E0B;"></i>
                        <strong>{{ $property->rating ?? '0' }}</strong>
                        <span style="color: #64748b;">({{ $property->review_count }} review)</span>
                    </div>
                    @if($property->rooms()->first())
                        <p style="margin: 0.5rem 0 1rem; font-weight: 600; color: var(--primary); font-size: 1rem;">
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
                    <div style="display: flex; gap: 0.5rem; margin-top: auto;">
                        <a href="{{ route('tenant.property-detail', $property) }}" class="btn btn-sm btn-outline-primary flex-grow-1">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12" style="text-align: center; padding: 3rem; background: white; border-radius: 12px;">
            <i class="bi bi-search" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
            <h6>Tidak Ada Hasil</h6>
            <p style="color: #64748b;">Coba ubah filter pencarian Anda</p>
        </div>
    @endforelse
</div>

{{ $properties->links('pagination::bootstrap-5') }}
@endsection
