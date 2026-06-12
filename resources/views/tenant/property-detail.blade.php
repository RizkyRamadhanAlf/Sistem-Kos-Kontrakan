@extends('tenant.layout')

@section('title', 'Detail Kos - KostKu')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('tenant.search') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-chevron-left"></i> Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <!-- Galeri Foto -->
        <div style="background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
            <img src="{{ $property->image_url }}" alt="{{ $property->name }}" style="width: 100%; height: 400px; object-fit: cover;">
        </div>

        <!-- Info Properti -->
        <div id="kamar" style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div>
                    <h5 style="margin: 0 0 0.5rem; font-weight: 700;">{{ $property->name }}</h5>
                    <p style="margin: 0; color: #64748b;">
                        <i class="bi bi-geo-fill"></i> {{ $property->location }}, {{ $property->city }}
                    </p>
                </div>
                <form action="{{ $isWishlisted ? route('tenant.wishlist.remove', $property) : route('tenant.wishlist.add', $property) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-{{ $isWishlisted ? 'danger' : 'outline-danger' }} btn-sm">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                </form>
            </div>

            <div style="display: flex; align-items: center; gap: 1rem; margin: 1rem 0;">
                <div>
                    <i class="bi bi-star-fill" style="color: #F59E0B; font-size: 1.2rem;"></i>
                </div>
                <div>
                    <strong style="font-size: 1.1rem;">{{ $property->rating }}</strong>
                    <span style="color: #64748b; margin-left: 0.5rem;">({{ $property->review_count }} review)</span>
                </div>
            </div>

            <hr>

            <h6 style="margin: 1rem 0 0.5rem; font-weight: 700;">Deskripsi</h6>
            <p style="margin: 0; color: #64748b; line-height: 1.6;">{{ $property->description }}</p>

            <h6 style="margin: 1.5rem 0 0.5rem; font-weight: 700;">Fasilitas</h6>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                @foreach($property->facilities ?? [] as $facility)
                    <span class="badge bg-light text-dark">{{ $facility }}</span>
                @endforeach
            </div>

            <h6 style="margin: 1.5rem 0 0.5rem; font-weight: 700;">Peraturan</h6>
            <ul style="margin: 0; padding-left: 1.5rem; color: #64748b;">
                @foreach($property->rules ?? [] as $rule)
                    <li>{{ $rule }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Kamar Tersedia -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
            <h6 style="margin: 0 0 1.5rem; font-weight: 700;">Kamar Tersedia</h6>

            <div class="row g-3">
                @foreach($property->rooms as $room)
                    <div class="col-md-6">
                        <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem;">
                            <h6 style="margin: 0 0 0.5rem; font-weight: 600;">{{ $room->room_number }}</h6>
                            <p style="margin: 0.25rem 0; font-size: 0.9rem; color: #64748b;">{{ $room->room_type }}</p>
                            <p style="margin: 0.25rem 0; font-size: 0.9rem; color: #64748b;">Kapasitas: {{ $room->capacity }} orang</p>
                            <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                                <p style="margin: 0; font-weight: 600; color: var(--primary); font-size: 1rem;">
                                    Rp {{ number_format($room->price_per_month, 0, ',', '.') }}/bln
                                </p>
                                @if($room->status === 'available')
                                    <form action="{{ route('tenant.booking.create', $property) }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                                        <div class="row g-2">
                                            <div class="col-7">
                                                <input type="date" name="check_in_date" min="{{ now()->toDateString() }}" value="{{ old('check_in_date', now()->addDay()->toDateString()) }}" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="col-5">
                                                <select name="duration_months" class="form-select form-select-sm" required>
                                                    @foreach([1, 3, 6, 12] as $month)
                                                        <option value="{{ $month }}">{{ $month }} bulan</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-primary w-100 mt-2">Pesan Sekarang</button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary w-100 mt-2" disabled>Tidak Tersedia</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Review -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
            <h6 style="margin: 0 0 1.5rem; font-weight: 700;">Review Pengguna</h6>

            @forelse($reviews as $review)
                <div style="padding-bottom: 1rem; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0;">
                    <div style="display: flex; gap: 1rem; align-items: start;">
                        <img src="{{ $review->user->profile_photo_url }}"
                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        <div style="flex: 1;">
                            <h6 style="margin: 0; font-weight: 600;">{{ $review->user->name }}</h6>
                            <div style="display: flex; gap: 0.25rem; margin: 0.25rem 0;">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <i class="bi bi-star-fill" style="color: #F59E0B; font-size: 0.9rem;"></i>
                                @endfor
                            </div>
                            <p style="margin: 0.5rem 0 0; color: #64748b; line-height: 1.5;">{{ $review->comment }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p style="color: #64748b; text-align: center;">Belum ada review</p>
            @endforelse

            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Ringkasan Harga -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; position: sticky; top: 20px;">
            <h6 style="margin: 0 0 1rem; font-weight: 700;">Informasi</h6>

            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0;">
                <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Harga Mulai dari</p>
                <p style="margin: 0.25rem 0 0; font-weight: 700; font-size: 1.3rem; color: var(--primary);">
                    Rp {{ number_format($property->rooms()->min('price_per_month'), 0, ',', '.') }}/bln
                </p>
            </div>

            <a href="{{ route('tenant.search') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-chevron-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
