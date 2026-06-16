<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'KostKu — Admin Dashboard')</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet"/>

  @vite(['resources/css/style.css'])
  @stack('css')
</head>
<body>

<div class="wrapper d-flex">
  <!-- ===================== SIDEBAR ===================== -->
  <aside class="sidebar d-flex flex-column">
    <!-- Brand -->
    <div class="sidebar-brand">
      <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
      <div>
        <span class="brand-name">KostKu</span>
        <span class="brand-sub">Admin Panel</span>
      </div>
    </div>

    <!-- Nav -->
    <nav class="sidebar-nav flex-grow-1">
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
      <a href="{{ route('pembayaran.verifikasi') }}" class="nav-item {{ request()->routeIs('pembayaran.verifikasi') ? 'active' : '' }}">
        <i class="bi bi-cash-stack"></i> Verifikasi Pembayaran
      </a>

      <p class="nav-label mt-3">Sistem</p>
      <a href="#" class="nav-item">
        <i class="bi bi-bar-chart-fill"></i> Laporan Global
      </a>
      <a href="#" class="nav-item">
        <i class="bi bi-gear-fill"></i> Pengaturan
      </a>
    </nav>

    <!-- User -->
    <div class="sidebar-user">
      <img src="https://i.pravatar.cc/40?img=3" alt="avatar" class="user-avatar"/>
      <div class="user-info">
        <span class="user-name">{{ auth()->user()->name ?? 'Guest' }}</span>
        <span class="user-role">Administrator</span>
      </div>
      <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link p-0">
          <i class="bi bi-box-arrow-right logout-icon"></i>
        </button>
      </form>
    </div>
  </aside>

  <!-- ===================== MAIN CONTENT ===================== -->
  <main class="main-content flex-grow-1">
    <!-- Topbar -->
    <header class="topbar d-flex align-items-center justify-content-between">
      <div>
        <h4 class="topbar-title">@yield('header_title', 'Dashboard')</h4>
        <p class="topbar-sub">{{ now()->locale('id_ID')->translatedFormat('l, d F Y') }}</p>
      </div>
      <div class="topbar-actions d-flex align-items-center gap-3">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Cari user, transaksi…"/>
        </div>
        <button class="btn-icon" title="Notifikasi">
          <i class="bi bi-bell-fill"></i>
          <span class="notif-dot"></span>
        </button>
        <img src="https://i.pravatar.cc/36?img=3" class="topbar-avatar" alt="avatar"/>
      </div>
    </header>

    <div class="content-body">
      @yield('content')
    </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('js')
</body>
</html>
