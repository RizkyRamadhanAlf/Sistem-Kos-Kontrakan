@extends('layouts.dashboard')

@section('sidebar_sub', 'Tenant Portal')
@section('user_role', 'Penyewa')

@section('sidebar_menu')
    <p class="nav-label">Menu Utama</p>
    <a href="{{ route('tenant.dashboard') }}" class="nav-item {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="{{ route('tenant.search') }}" class="nav-item {{ request()->routeIs('tenant.search', 'tenant.property-detail') ? 'active' : '' }}">
        <i class="bi bi-search"></i> Cari Kos
    </a>
    <a href="{{ route('tenant.bookings') }}" class="nav-item {{ request()->routeIs('tenant.bookings*', 'tenant.booking-detail') ? 'active' : '' }}">
        <i class="bi bi-calendar-check-fill"></i> Booking Saya
    </a>
    <a href="{{ route('tenant.payments') }}" class="nav-item {{ request()->routeIs('tenant.payments*', 'tenant.payment-detail') ? 'active' : '' }}">
        <i class="bi bi-credit-card-fill"></i> Pembayaran
    </a>
    <a href="{{ route('tenant.complaints.index') }}" class="nav-item {{ request()->routeIs('tenant.complaints.*') ? 'active' : '' }}">
        <i class="bi bi-chat-left-dots-fill"></i> Komplain Saya
    </a>

    <p class="nav-label mt-3">Aktivitas</p>
    <a href="{{ route('tenant.transactions') }}" class="nav-item {{ request()->routeIs('tenant.transactions') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i> Riwayat Transaksi
    </a>
    <a href="{{ route('tenant.notifications') }}" class="nav-item {{ request()->routeIs('tenant.notifications') ? 'active' : '' }}">
        <i class="bi bi-bell-fill"></i> Notifikasi
        @php($unreadNotifications = Auth::user()->notifications()->whereNull('read_at')->count())
        @if($unreadNotifications)
            <span class="badge-nav danger">{{ $unreadNotifications }}</span>
        @endif
    </a>
    <a href="{{ route('tenant.wishlist') }}" class="nav-item {{ request()->routeIs('tenant.wishlist') ? 'active' : '' }}">
        <i class="bi bi-heart-fill"></i> Wishlist
    </a>

    <p class="nav-label mt-3">Akun</p>
    <a href="{{ route('tenant.profile') }}" class="nav-item {{ request()->routeIs('tenant.profile') ? 'active' : '' }}">
        <i class="bi bi-person-fill"></i> Profil Saya
    </a>
@endsection

@section('header_title')
    @yield('title', 'Dashboard')
@endsection

@section('content')
    @yield('content')
@endsection
