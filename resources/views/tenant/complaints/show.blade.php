@extends('tenant.layout')

@section('title', 'Detail Komplain - KostKu')

@section('content')
@php
    $statusClasses = ['pending' => 'warning', 'in_progress' => 'primary', 'resolved' => 'success', 'rejected' => 'danger'];
@endphp

<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('tenant.complaints.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-chevron-left"></i> Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; margin-bottom: 1rem;">
            <div class="d-flex justify-content-between gap-3 flex-wrap">
                <div>
                    <h5 class="fw-bold mb-1">{{ $complaint->title }}</h5>
                    <p class="text-secondary mb-0">Tiket {{ $complaint->ticket_number }}</p>
                </div>
                <span class="badge bg-{{ $statusClasses[$complaint->status] ?? 'secondary' }} align-self-start">{{ $complaint->status_label }}</span>
            </div>
            <hr>
            <div class="row g-3">
                <div class="col-md-6"><small class="text-secondary">Nama Kos</small><div class="fw-semibold">{{ $complaint->booking?->room?->property?->name ?? $complaint->booking?->kos_name }}</div></div>
                <div class="col-md-6"><small class="text-secondary">Nomor Kamar</small><div class="fw-semibold">{{ $complaint->booking?->room?->room_number ?? '-' }}</div></div>
                <div class="col-md-4"><small class="text-secondary">Kategori</small><div class="fw-semibold">{{ $complaint->category_label }}</div></div>
                <div class="col-md-4"><small class="text-secondary">Prioritas</small><div class="fw-semibold">{{ $complaint->priority_label }}</div></div>
                <div class="col-md-4"><small class="text-secondary">Tanggal Pengajuan</small><div class="fw-semibold">{{ $complaint->created_at->format('d M Y H:i') }}</div></div>
            </div>
            <div class="mt-4">
                <h6 class="fw-bold">Deskripsi</h6>
                <p class="text-secondary mb-0" style="white-space: pre-line;">{{ $complaint->description }}</p>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; margin-bottom: 1rem;">
            <h6 class="fw-bold mb-3">Foto Bukti</h6>
            <div class="row g-2">
                @forelse($complaint->images as $image)
                    <div class="col-md-4">
                        <a href="{{ $image->image_url }}" target="_blank">
                            <img src="{{ $image->image_url }}" class="img-fluid rounded-3 border" style="height: 150px; width: 100%; object-fit: cover;">
                        </a>
                    </div>
                @empty
                    <p class="text-secondary mb-0">Tidak ada foto bukti.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
            <h6 class="fw-bold mb-3">Tanggapan Pemilik Kos</h6>
            <div class="d-flex flex-column gap-3 mb-3" style="max-height: 420px; overflow:auto;">
                @forelse($complaint->replies as $reply)
                    <div class="p-3 rounded-3 {{ $reply->user_id === auth()->id() ? 'bg-primary-subtle ms-4' : 'bg-light me-4' }}">
                        <div class="d-flex justify-content-between gap-2">
                            <strong class="small">{{ $reply->user_id === auth()->id() ? 'Anda' : $reply->user?->name }}</strong>
                            <small class="text-secondary">{{ $reply->created_at->format('d M H:i') }}</small>
                        </div>
                        <p class="mb-0 mt-2 small" style="white-space: pre-line;">{{ $reply->message }}</p>
                    </div>
                @empty
                    <p class="text-secondary small">Belum ada tanggapan.</p>
                @endforelse
            </div>

            @if(!in_array($complaint->status, ['resolved', 'rejected'], true))
                <form action="{{ route('tenant.complaints.reply', $complaint) }}" method="POST">
                    @csrf
                    <textarea name="message" class="form-control mb-2" rows="3" placeholder="Tulis balasan..." required></textarea>
                    <button class="btn btn-primary w-100"><i class="bi bi-send-fill"></i> Kirim Balasan</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
