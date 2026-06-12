@extends('owner.layout')

@section('title', 'Komplain Penyewa')
@section('heading', 'Komplain Penyewa')

@section('content')
@php
    $statusClasses = ['pending' => 's-pending', 'in_progress' => 's-booked', 'resolved' => 's-paid', 'rejected' => 's-rejected'];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Komplain Penyewa</h4>
        <p class="sub mb-0">Tinjau, tanggapi, dan selesaikan pengaduan penyewa.</p>
    </div>
    <form class="d-flex gap-2">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach($statuses as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Tiket</th>
                    <th>Penyewa</th>
                    <th>Kos</th>
                    <th>Kategori</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $complaint)
                    <tr>
                        <td><strong>{{ $complaint->ticket_number }}</strong><br><span class="sub">{{ $complaint->created_at->format('d M Y') }}</span></td>
                        <td>{{ $complaint->user?->name }}</td>
                        <td>{{ $complaint->booking?->room?->property?->name ?? $complaint->booking?->kos_name }}<br><span class="sub">Kamar {{ $complaint->booking?->room?->room_number ?? '-' }}</span></td>
                        <td>{{ $complaint->category_label }}</td>
                        <td>{{ $complaint->priority_label }}</td>
                        <td><span class="status {{ $statusClasses[$complaint->status] ?? 's-expired' }}">{{ $complaint->status_label }}</span></td>
                        <td><a href="{{ route('owner.complaints.show', $complaint) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada komplain penyewa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $complaints->links('pagination::bootstrap-5') }}
</div>
@endsection
