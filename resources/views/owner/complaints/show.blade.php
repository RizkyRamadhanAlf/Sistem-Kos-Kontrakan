@extends('owner.layout')

@section('title', 'Detail Komplain')
@section('heading', 'Detail Komplain')

@section('content')
@php
    $statusClasses = ['pending' => 's-pending', 'in_progress' => 's-booked', 'resolved' => 's-paid', 'rejected' => 's-rejected'];
@endphp

<div class="mb-3">
    <a href="{{ route('owner.complaints.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i> Kembali</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="panel mb-3">
            <div class="d-flex justify-content-between gap-3 flex-wrap">
                <div>
                    <h4 class="fw-bold mb-1">{{ $complaint->title }}</h4>
                    <p class="sub mb-0">Tiket {{ $complaint->ticket_number }}</p>
                </div>
                <span class="status {{ $statusClasses[$complaint->status] ?? 's-expired' }}">{{ $complaint->status_label }}</span>
            </div>
            <hr>
            <div class="row g-3">
                <div class="col-md-6"><span class="sub">Penyewa</span><div class="fw-semibold">{{ $complaint->user?->name }}</div></div>
                <div class="col-md-6"><span class="sub">Kontak</span><div class="fw-semibold">{{ $complaint->user?->phone ?? '-' }}</div></div>
                <div class="col-md-6"><span class="sub">Kos</span><div class="fw-semibold">{{ $complaint->booking?->room?->property?->name ?? $complaint->booking?->kos_name }}</div></div>
                <div class="col-md-6"><span class="sub">Kamar</span><div class="fw-semibold">{{ $complaint->booking?->room?->room_number ?? '-' }}</div></div>
                <div class="col-md-4"><span class="sub">Kategori</span><div class="fw-semibold">{{ $complaint->category_label }}</div></div>
                <div class="col-md-4"><span class="sub">Prioritas</span><div class="fw-semibold">{{ $complaint->priority_label }}</div></div>
                <div class="col-md-4"><span class="sub">Tanggal</span><div class="fw-semibold">{{ $complaint->created_at->format('d M Y H:i') }}</div></div>
            </div>
            <div class="mt-4">
                <h6 class="fw-bold">Deskripsi</h6>
                <p class="mb-0 text-muted" style="white-space: pre-line;">{{ $complaint->description }}</p>
            </div>
        </div>

        <div class="panel">
            <h6 class="fw-bold mb-3">Foto Bukti</h6>
            <div class="row g-2">
                @forelse($complaint->images as $image)
                    <div class="col-md-4">
                        <a href="{{ $image->image_url }}" target="_blank">
                            <img src="{{ $image->image_url }}" class="img-fluid rounded-3 border" style="height: 150px; width: 100%; object-fit: cover;">
                        </a>
                    </div>
                @empty
                    <p class="text-muted mb-0">Tidak ada foto bukti.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel mb-3">
            <h6 class="fw-bold mb-3">Ubah Status</h6>
            <form action="{{ route('owner.complaints.status', $complaint) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="status" class="form-select mb-2" required>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($complaint->status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn btn-teal w-100">Simpan Status</button>
            </form>
        </div>

        <div class="panel">
            <h6 class="fw-bold mb-3">Tanggapan</h6>
            <div class="d-flex flex-column gap-3 mb-3" style="max-height: 360px; overflow:auto;">
                @forelse($complaint->replies as $reply)
                    <div class="p-3 rounded-3 {{ $reply->user_id === auth()->id() ? 'bg-success-subtle ms-3' : 'bg-light me-3' }}">
                        <div class="d-flex justify-content-between gap-2">
                            <strong class="small">{{ $reply->user_id === auth()->id() ? 'Anda' : $reply->user?->name }}</strong>
                            <small class="text-muted">{{ $reply->created_at->format('d M H:i') }}</small>
                        </div>
                        <p class="mb-0 mt-2 small" style="white-space: pre-line;">{{ $reply->message }}</p>
                    </div>
                @empty
                    <p class="text-muted small">Belum ada tanggapan.</p>
                @endforelse
            </div>
            <form action="{{ route('owner.complaints.reply', $complaint) }}" method="POST">
                @csrf
                <textarea name="message" class="form-control mb-2" rows="3" placeholder="Contoh: Teknisi akan datang besok pukul 10.00 WIB..." required></textarea>
                <button class="btn btn-teal w-100"><i class="bi bi-send-fill"></i> Kirim Tanggapan</button>
            </form>
        </div>
    </div>
</div>
@endsection
