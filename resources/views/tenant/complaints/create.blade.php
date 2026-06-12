@extends('tenant.layout')

@section('title', 'Buat Komplain - KostKu')

@section('content')
<div class="content-header">
    <div class="header-title">
        <h1>Buat Komplain</h1>
        <p>Laporkan masalah pada kos yang sedang Anda tempati</p>
    </div>
</div>

<div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
    @if($bookings->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-house-exclamation" style="font-size: 3rem; color: #cbd5e1;"></i>
            <h6 class="mt-3">Tidak ada booking aktif</h6>
            <p class="text-secondary">Komplain hanya dapat dibuat untuk booking yang sudah aktif.</p>
            <a href="{{ route('tenant.bookings') }}" class="btn btn-outline-primary">Lihat Booking Saya</a>
        </div>
    @else
        <form action="{{ route('tenant.complaints.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Pilih Booking Aktif</label>
                    <select name="booking_id" class="form-select @error('booking_id') is-invalid @enderror" required>
                        <option value="">Pilih kos dan kamar</option>
                        @foreach($bookings as $booking)
                            <option value="{{ $booking->id }}" @selected(old('booking_id') == $booking->id)>
                                {{ $booking->room?->property?->name ?? $booking->kos_name }} - Kamar {{ $booking->room?->room_number ?? $booking->room_type }}
                            </option>
                        @endforeach
                    </select>
                    @error('booking_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori Komplain</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Prioritas</label>
                    <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                        <option value="">Pilih prioritas</option>
                        @foreach($priorities as $value => $label)
                            <option value="{{ $value }}" @selected(old('priority') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Judul Komplain</label>
                    <input name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="AC kamar tidak berfungsi" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Deskripsi Komplain</label>
                    <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Jelaskan masalah yang Anda alami..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Upload Foto Bukti</label>
                    <input type="file" name="images[]" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" accept="image/png,image/jpeg" multiple>
                    <small class="text-secondary">Maksimal 3 foto, format JPG/JPEG/PNG, ukuran maksimal 2MB per foto.</small>
                    @error('images')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    @error('images.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary"><i class="bi bi-send-fill"></i> Kirim Komplain</button>
                <a href="{{ route('tenant.complaints.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    @endif
</div>
@endsection
