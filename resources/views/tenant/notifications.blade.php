@extends('tenant.layout')

@section('title', 'Notifikasi - KostKu')

@section('content')
<div class="content-header">
    <div class="header-title">
        <h1>Notifikasi</h1>
        <p>Informasi terbaru tentang booking dan pembayaran Anda</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        @forelse($notifications as $notification)
            <div style="background: white; border-radius: 12px; padding: 1.5rem; border-left: 4px solid {{ $notification->isRead() ? '#cbd5e1' : 'var(--primary)' }}; margin-bottom: 1rem; display: flex; gap: 1rem;">
                <div style="width: 50px; height: 50px; background: {{ $notification->isRead() ? '#f1f5f9' : 'var(--light)' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: {{ $notification->isRead() ? '#94a3b8' : 'var(--primary)' }}; flex-shrink: 0; font-size: 1.5rem;">
                    @switch($notification->type)
                        @case('booking')
                            <i class="bi bi-calendar-check-fill"></i>
                        @break
                        @case('payment')
                            <i class="bi bi-credit-card-fill"></i>
                        @break
                        @case('promo')
                            <i class="bi bi-tag-fill"></i>
                        @break
                        @default
                            <i class="bi bi-bell-fill"></i>
                    @endswitch
                </div>
                <div style="flex: 1;">
                    <h6 style="margin: 0 0 0.5rem; font-weight: {{ $notification->isRead() ? '400' : '600' }}; color: {{ $notification->isRead() ? '#64748b' : 'var(--dark)' }};">
                        {{ $notification->title }}
                    </h6>
                    <p style="margin: 0; font-size: 0.9rem; color: #64748b;">{{ $notification->message }}</p>
                    <small style="color: #94a3b8; margin-top: 0.5rem; display: block;">
                        {{ $notification->created_at->diffForHumans() }}
                    </small>
                </div>
                @if(!$notification->isRead())
                    <form action="{{ route('tenant.notification.read', $notification) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light" title="Tandai sebagai dibaca">
                            <i class="bi bi-check"></i>
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px;">
                <i class="bi bi-inbox" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                <h6>Tidak Ada Notifikasi</h6>
                <p style="color: #64748b;">Anda sudah mengikuti semua perkembangan</p>
            </div>
        @endforelse

        {{ $notifications->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
