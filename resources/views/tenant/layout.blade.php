<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KostKu - Dashboard Penyewa')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    @vite(['resources/css/style.css'])

    <style>
        :root {
            --primary: var(--teal);
            --primary-dark: #0f766e;
            --success: var(--green);
            --warning: var(--amber);
            --danger: var(--rose);
            --light: var(--bg);
            --dark: var(--text-primary);
        }
        .sidebar { position: fixed; left: 0; z-index: 1000; }
        .main-content { margin-left: var(--sidebar-w); min-height: 100vh; }
        .content-body { flex: 1; }
        .sidebar-overlay, .mobile-menu { display: none; }
        .sidebar-user form { margin-left: auto; }
        .logout-icon { border: 0; background: transparent; padding: 0; }
        .topbar-avatar { object-fit: cover; }
        .content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }
        .header-title h1 {
            color: var(--text-primary);
            font-size: 20px;
            font-weight: 800;
            margin: 0;
        }
        .header-title p {
            color: var(--text-secondary);
            font-size: 12.5px;
            margin: 3px 0 0;
        }
        .header-actions { display: flex; gap: 10px; }
        .btn-primary {
            --bs-btn-bg: var(--teal);
            --bs-btn-border-color: var(--teal);
            --bs-btn-hover-bg: #0f766e;
            --bs-btn-hover-border-color: #0f766e;
        }
        .btn-outline-primary {
            --bs-btn-color: var(--teal);
            --bs-btn-border-color: var(--teal);
            --bs-btn-hover-bg: var(--teal);
            --bs-btn-hover-border-color: var(--teal);
        }
        .table { --bs-table-bg: transparent; color: var(--text-primary); }
        .main-content [style*="background: white"] {
            background: var(--surface) !important;
            border-color: var(--border) !important;
            box-shadow: var(--shadow);
        }
        .main-content [style*="border-radius: 12px"] { border-radius: var(--radius) !important; }
        .main-content img { transition: transform .25s ease; }
        .main-content [style*="overflow: hidden"]:hover > img { transform: scale(1.025); }
        .table thead th {
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .4px;
            text-transform: uppercase;
        }
        .pagination { margin-top: 22px; }
        .page-link { color: var(--teal); }
        .active > .page-link { background: var(--teal); border-color: var(--teal); }

        [data-theme="dark"] {
            --bg: #0f172a;
            --surface: #172033;
            --border: #29344a;
            --text-primary: #e2e8f0;
            --text-secondary: #a5b4c7;
            --text-muted: #7f8da3;
        }
        [data-theme="dark"] .main-content [style*="background: white"],
        [data-theme="dark"] .main-content [style*="background-color: var(--light)"] {
            background: var(--surface) !important;
            border-color: var(--border) !important;
        }
        [data-theme="dark"] .table {
            --bs-table-color: var(--text-primary);
            --bs-table-border-color: var(--border);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                display: flex !important;
                transform: translateX(-100%);
                transition: transform .25s ease;
            }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .mobile-menu { display: flex; }
            .sidebar-overlay.open {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 999;
                background: rgba(15, 25, 35, .65);
            }
            .topbar { padding: 14px 18px; }
            .content-body { padding: 20px 16px 28px; }
            .search-box { display: none; }
        }

        @media (max-width: 575.98px) {
            .topbar-sub { display: none; }
            .topbar-actions { gap: 8px !important; }
            .topbar-avatar { display: none; }
            .content-header { align-items: flex-start; flex-direction: column; }
            .header-actions, .header-actions .btn { width: 100%; }
            .btn-group { display: flex; flex-wrap: wrap; }
        }
    </style>
    @stack('css')
</head>
<body>
    <div class="wrapper d-flex">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <aside class="sidebar d-flex flex-column">
            <div class="sidebar-brand">
                <div class="brand-icon"><i class="bi bi-buildings-fill"></i></div>
                <div>
                    <span class="brand-name">KostKu</span>
                    <span class="brand-sub">Tenant Portal</span>
                </div>
            </div>

            <nav class="sidebar-nav flex-grow-1">
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
                <a href="{{ route('tenant.wishlist') }}" class="nav-item {{ request()->routeIs('tenant.wishlist') ? 'active' : '' }}">
                    <i class="bi bi-heart-fill"></i> Wishlist
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

                <p class="nav-label mt-3">Akun</p>
                <a href="{{ route('tenant.profile') }}" class="nav-item {{ request()->routeIs('tenant.profile') ? 'active' : '' }}">
                    <i class="bi bi-person-fill"></i> Profil Saya
                </a>
            </nav>

            <div class="sidebar-user">
                <img src="{{ Auth::user()->profile_photo_url }}" alt="avatar" class="user-avatar">
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role">Penyewa</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-icon" title="Logout"><i class="bi bi-box-arrow-right"></i></button>
                </form>
            </div>
        </aside>

        <main class="main-content flex-grow-1">
            <header class="topbar d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn-icon mobile-menu" id="sidebarToggle" aria-label="Buka menu"><i class="bi bi-list"></i></button>
                    <div>
                        <h4 class="topbar-title">Selamat Datang, {{ Auth::user()->name }}</h4>
                        <p class="topbar-sub">{{ now()->locale('id_ID')->translatedFormat('l, d F Y') }} · Kelola hunian Anda hari ini</p>
                    </div>
                </div>
                <div class="topbar-actions d-flex align-items-center gap-3">
                    <form action="{{ route('tenant.search') }}" method="GET" class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" placeholder="Cari kos atau lokasi...">
                    </form>
                    <button class="btn-icon theme-toggle" title="Ubah tema"><i class="bi bi-moon-stars-fill"></i></button>
                    <a href="{{ route('tenant.notifications') }}" class="btn-icon" title="Notifikasi">
                        <i class="bi bi-bell-fill"></i>
                        @if($unreadNotifications)<span class="notif-dot"></span>@endif
                    </a>
                    <a href="{{ route('tenant.profile') }}">
                        <img src="{{ Auth::user()->profile_photo_url }}" class="topbar-avatar" alt="avatar">
                    </a>
                </div>
            </header>

            <div class="content-body">
                @foreach(['success', 'error', 'info'] as $messageType)
                    @if($message = session($messageType))
                        <div class="alert alert-{{ $messageType === 'error' ? 'danger' : $messageType }} alert-dismissible fade show" role="alert">
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                @endforeach

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const root = document.documentElement;
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (localStorage.getItem('kostku-theme') === 'dark') root.dataset.theme = 'dark';
        document.querySelectorAll('.theme-toggle').forEach(button => button.addEventListener('click', () => {
            root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('kostku-theme', root.dataset.theme);
        }));
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        });
    </script>
    @stack('js')
</body>
</html>
