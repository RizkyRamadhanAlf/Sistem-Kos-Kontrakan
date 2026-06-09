<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KostKu — Dashboard</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />

  @vite(['resources/css/style.css'])
</head>

<body>

  <!-- ===================== SIDEBAR ===================== -->
  <div class="wrapper d-flex">

    <aside class="sidebar d-flex flex-column">
      <!-- Brand -->
      <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-buildings-fill"></i></div>
        <div>
          <span class="brand-name">KostKu</span>
          <span class="brand-sub">Property Manager</span>
        </div>
      </div>

      <!-- Nav -->
      <nav class="sidebar-nav flex-grow-1">
        <p class="nav-label">Menu Utama</p>
        <a href="#" class="nav-item active">
          <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="#" class="nav-item">
          <i class="bi bi-house-door-fill"></i> Properti
          <span class="badge-nav">12</span>
        </a>
        <a href="#" class="nav-item">
          <i class="bi bi-people-fill"></i> Penyewa
          <span class="badge-nav">47</span>
        </a>
        <a href="#" class="nav-item">
          <i class="bi bi-cash-coin"></i> Pembayaran
        </a>
        <a href="#" class="nav-item">
          <i class="bi bi-file-earmark-text-fill"></i> Kontrak
        </a>

        <p class="nav-label mt-3">Operasional</p>
        <a href="#" class="nav-item">
          <i class="bi bi-wrench-adjustable-circle-fill"></i> Pemeliharaan
          <span class="badge-nav danger">3</span>
        </a>
        <a href="#" class="nav-item">
          <i class="bi bi-megaphone-fill"></i> Pengumuman
        </a>
        <a href="#" class="nav-item">
          <i class="bi bi-bar-chart-fill"></i> Laporan
        </a>

        <p class="nav-label mt-3">Akun</p>
        <a href="{{ route('profile.edit') }}" class="nav-item">
          <i class="bi bi-gear-fill"></i> Pengaturan
        </a>
      </nav>

      <!-- User -->
      <div class="sidebar-user">
        <img src="https://i.pravatar.cc/40?img=12" alt="avatar" class="user-avatar" />
        <div class="user-info">
          <span class="user-name">Budi Santoso</span>
          <span class="user-role">Pemilik</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <x-responsive-nav-link :href="route('logout')"
            onclick="event.preventDefault();
                                        this.closest('form').submit();">
            {{ __('Log Out') }}
          </x-responsive-nav-link>
        </form>
      </div>
    </aside>

    <!-- ===================== MAIN CONTENT ===================== -->
    <main class="main-content flex-grow-1">

      <!-- Topbar -->
      <header class="topbar d-flex align-items-center justify-content-between">
        <div>
          <h4 class="topbar-title">Selamat Datang, {{ auth()->user()->name }} 👋</h4>
          <p class="topbar-sub">{{ now()->format('l, d F Y') }} · Ringkasan akun Anda hari ini</p>
        </div>
        <div class="topbar-actions d-flex align-items-center gap-3">
          <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari tagihan, maintenance…" />
          </div>
          <button class="btn-icon" title="Notifikasi">
            <i class="bi bi-bell-fill"></i>
          </button>
          <img src="https://i.pravatar.cc/36?img=12" class="topbar-avatar" alt="avatar" />
        </div>
      </header>

      <!-- ===================== STAT CARDS ===================== -->
      <div class="content-body">
        <div class="row g-4 mb-4">
          <div class="col-xl-4 col-sm-6">
            <div class="stat-card card-teal">
              <div class="stat-icon"><i class="bi bi-calendar-check-fill"></i></div>
              <div class="stat-info">
                <span class="stat-label">Total Booking</span>
                <span class="stat-value">{{ $bookings->count() }}</span>
              </div>
            </div>
          </div>
          <div class="col-xl-4 col-sm-6">
            <div class="stat-card card-rose">
              <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
              <div class="stat-info">
                <span class="stat-label">Belum Dibayar</span>
                <span class="stat-value">{{ $bookings->where('status', 'pending')->count() }}</span>
              </div>
            </div>
          </div>
          <div class="col-xl-4 col-sm-6">
            <div class="stat-card card-green">
              <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
              <div class="stat-info">
                <span class="stat-label">Booking Aktif</span>
                <span class="stat-value">{{ $bookings->where('status', 'paid')->count() }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- ===================== MID ROW ===================== -->
        <div class="row g-4 mb-4">

          <!-- Riwayat Booking -->
          <div class="col-xl-12">
            <div class="panel">
              <div class="panel-header">
                <div>
                  <h6 class="panel-title">Riwayat Booking & Pembayaran</h6>
                  <p class="panel-sub">Daftar booking yang Anda lakukan</p>
                </div>
                <a href="{{ route('booking.create') }}" class="btn-add"><i class="bi bi-plus-lg"></i> Booking Baru</a>
              </div>

              <div class="property-list">
                @forelse($bookings as $booking)
                <div class="property-item">
                  <div class="prop-thumb" style="background:#e0f2f1;">
                    <i class="bi bi-building" style="color:#0d9488;"></i>
                  </div>
                  <div class="prop-info">
                    <span class="prop-name">{{ $booking->kos_name }} ({{ $booking->room_type }})</span>
                    <span class="prop-addr"><i class="bi bi-geo-alt-fill"></i> {{ $booking->location }}</span>
                    <span class="small text-muted">Tanggal Masuk: {{ optional($booking->booking_date)->format('d M Y') }}</span>
                  </div>
                  <div class="prop-stat">
                    <span class="fw-bold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                    <span class="small text-muted text-center d-block">{{ $booking->duration_months }} Bulan</span>
                  </div>
                  <span class="prop-badge {{ $booking->status === 'paid' ? 'bg-success' : 'bg-warning' }} text-white">
                    {{ ucfirst($booking->status) }}
                  </span>
                  <div class="prop-actions">
                    @if($booking->status === 'pending')
                    <a href="{{ route('booking.payment.show', $booking->id) }}" class="btn btn-primary btn-sm">Bayar Sekarang</a>
                    @else
                    <button class="btn btn-outline-secondary btn-sm" disabled>Sudah Lunas</button>
                    @endif
                  </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada riwayat booking.</p>
                    <a href="{{ route('booking.create') }}" class="btn btn-primary">Mulai Cari Kos</a>
                </div>
                @endforelse
              </div>
            </div>
          </div>
        </div>
        <!-- end mid row -->

      </div><!-- end content-body -->
    </main>
  </div><!-- end wrapper -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>