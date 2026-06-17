@extends('layouts.dashboard')

@section('sidebar_sub', 'Owner Portal')
@section('user_role', 'Pemilik')

@section('sidebar_menu')
    <div class="owner-meta p-2 mb-2 bg-dark rounded bg-opacity-10">
        <small class="text-white-50 d-block">Pemilik Terverifikasi</small>
        <strong class="text-white d-block">{{ auth()->user()->name }}</strong>
        <small class="text-white-50">{{ auth()->user()->properties()->where('status','active')->count() }} properti aktif</small>
    </div>
    
    <p class="nav-label">Bisnis</p>
    @foreach([
      ['dashboard.owner','bi-grid-1x2-fill','Dashboard'],
      ['owner.properties','bi-houses-fill','Properti Saya'],
      ['owner.rooms','bi-door-open-fill','Kamar Kos'],
      ['owner.bookings','bi-calendar-check-fill','Booking Masuk'],
      ['owner.tenants','bi-people-fill','Penyewa'],
      ['owner.payments','bi-credit-card-fill','Pembayaran'],
      ['owner.complaints.index','bi-chat-left-dots-fill','Komplain Penyewa'],
      ['owner.revenue','bi-graph-up-arrow','Pendapatan'],
      ['owner.reports','bi-file-earmark-bar-graph-fill','Laporan'],
      ['owner.notifications','bi-bell-fill','Notifikasi']
    ] as [$route,$icon,$label])
        <a href="{{ route($route) }}" class="nav-item {{ request()->routeIs($route) ? 'active':'' }}">
            <i class="bi {{ $icon }}"></i> {{ $label }}
        </a>
    @endforeach

    <p class="nav-label mt-3">Akun</p>
    <a href="{{ route('owner.profile') }}" class="nav-item {{ request()->routeIs('owner.profile') ? 'active':'' }}">
        <i class="bi bi-person-circle"></i> Profil
    </a>
    <a href="{{ route('owner.settings') }}" class="nav-item {{ request()->routeIs('owner.settings') ? 'active':'' }}">
        <i class="bi bi-gear-fill"></i> Pengaturan
    </a>
@endsection

@section('header_title')
    @yield('heading', 'Owner Dashboard')
@endsection

@section('content')
    @foreach(['success', 'error'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type==='error'?'danger':'success' }} alert-dismissible fade show" role="alert">
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach
    @yield('content')
@endsection
