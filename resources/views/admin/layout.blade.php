@extends('layouts.dashboard')

@section('sidebar_sub', 'Admin Panel')
@section('user_role', 'Administrator')

@section('sidebar_menu')
    <p class="nav-label">Menu Utama</p>
    <a href="{{ route('dashboard.admin') }}" class="nav-item {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> Manajemen User
    </a>
    <a href="#" class="nav-item">
        <i class="bi bi-building-fill"></i> Data Properti
    </a>

    <p class="nav-label mt-3">Sistem</p>
    <a href="#" class="nav-item">
        <i class="bi bi-bar-chart-fill"></i> Laporan Global
    </a>
    <a href="#" class="nav-item">
        <i class="bi bi-gear-fill"></i> Pengaturan
    </a>
@endsection

@section('header_title')
    @yield('header_title', 'Admin Dashboard')
@endsection

@section('content')
    @yield('content')
@endsection
