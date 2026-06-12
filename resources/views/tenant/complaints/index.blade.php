@extends('tenant.layout')

@section('title', 'Komplain Saya - KostKu')

@section('content')
@php
    $statusClasses = ['pending' => 'warning', 'in_progress' => 'primary', 'resolved' => 'success', 'rejected' => 'danger'];
@endphp

<div class="content-header">
    <div class="header-title">
        <h1>Komplain Saya</h1>
        <p>Pantau status pengaduan dan tanggapan dari pemilik kos</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('tenant.complaints.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Buat Komplain
        </a>
    </div>
</div>

<div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nomor Tiket</th>
                    <th>Judul Komplain</th>
                    <th>Nama Kos</th>
                    <th>Nomor Kamar</th>
                    <th>Tanggal</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $complaint)
                    <tr>
                        <td><strong>{{ $complaint->ticket_number }}</strong></td>
                        <td>{{ $complaint->title }}</td>
                        <td>{{ $complaint->booking?->room?->property?->name ?? $complaint->booking?->kos_name }}</td>
                        <td>{{ $complaint->booking?->room?->room_number ?? '-' }}</td>
                        <td>{{ $complaint->created_at->format('d M Y') }}</td>
                        <td>{{ $complaint->priority_label }}</td>
                        <td><span class="badge bg-{{ $statusClasses[$complaint->status] ?? 'secondary' }}">{{ $complaint->status_label }}</span></td>
                        <td><a href="{{ route('tenant.complaints.show', $complaint) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-chat-left-dots" style="font-size: 3rem; color: #cbd5e1;"></i>
                            <h6 class="mt-3">Belum ada komplain</h6>
                            <p class="text-secondary mb-0">Buat komplain jika ada kendala pada kos aktif Anda.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $complaints->links('pagination::bootstrap-5') }}
</div>
@endsection
