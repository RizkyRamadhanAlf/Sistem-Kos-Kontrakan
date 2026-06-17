<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'KostKu'))</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|dm-serif-display:400,400i" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
    @stack('css')
</head>
<body class="font-sans antialiased">
    <div class="wrapper d-flex">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar d-flex flex-column">
            <div class="sidebar-brand">
                <div class="brand-icon">
                     <x-application-logo class="h-10 w-auto" />
                </div>
                <div>
                    <span class="brand-name">KostKu</span>
                    <span class="brand-sub">@yield('sidebar_sub', 'Portal')</span>
                </div>
            </div>

            <nav class="sidebar-nav flex-grow-1">
                @yield('sidebar_menu')
            </nav>

            <div class="sidebar-user">
                <img src="{{ auth()->user()->profile_photo_url ?? 'https://i.pravatar.cc/40' }}" alt="avatar" class="user-avatar">
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">@yield('user_role', 'User')</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-icon" title="Logout"><i class="bi bi-box-arrow-right"></i></button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex-grow-1">
            <!-- Topbar -->
            <header class="topbar d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn-icon mobile-menu" id="sidebarToggle" aria-label="Buka menu"><i class="bi bi-list"></i></button>
                    <div>
                        <h4 class="topbar-title">@yield('header_title', 'Dashboard')</h4>
                        <p class="topbar-sub">{{ now()->locale('id_ID')->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                <div class="topbar-actions d-flex align-items-center gap-3">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Cari sesuatu...">
                    </div>
                    <button class="btn-icon" title="Notifikasi">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notif-dot"></span>
                    </button>
                    <img src="{{ auth()->user()->profile_photo_url ?? 'https://i.pravatar.cc/36' }}" class="topbar-avatar" alt="avatar">
                </div>
            </header>

            <!-- Content Body -->
            <div class="content-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('open');
                    overlay.classList.toggle('open');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('open');
                });
            }
        });
    </script>
    @stack('js')
</body>
</html>
