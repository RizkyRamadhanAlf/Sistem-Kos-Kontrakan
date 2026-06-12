<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Owner Dashboard') - KostKu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{--nav:#101d2b;--teal:#0d9488;--bg:#f4f7fa;--ink:#122033;--muted:#718096;--line:#e5eaf0;--card:#fff}
    *{box-sizing:border-box}body{background:var(--bg);color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif;margin:0}.shell{display:flex;min-height:100vh}
    .side{background:var(--nav);color:#fff;display:flex;flex-direction:column;position:fixed;inset:0 auto 0 0;width:258px;z-index:10}.brand{align-items:center;display:flex;font-size:22px;font-weight:800;gap:10px;padding:22px}.brand i{background:var(--teal);border-radius:10px;padding:8px}.owner-meta{background:#17293b;border-radius:12px;margin:0 14px 15px;padding:12px}.owner-meta small{color:#a6b7c8}.owner-meta strong{display:block;font-size:13px}.nav-links{overflow:auto;padding:5px 12px}.nav-links a{align-items:center;border-radius:9px;color:#aebdca;display:flex;font-size:12px;font-weight:600;gap:11px;margin:2px 0;padding:10px 12px;text-decoration:none}.nav-links a:hover,.nav-links a.active{background:#20374d;color:#fff}.nav-links .label{color:#607990;font-size:9px;font-weight:800;letter-spacing:1px;margin:16px 10px 5px;text-transform:uppercase}.badge-count{background:#dc3545;border-radius:9px;color:#fff;font-size:9px;margin-left:auto;padding:2px 6px}.logout{border-top:1px solid #263a4d;margin-top:auto;padding:14px}.main{margin-left:258px;width:calc(100% - 258px)}.top{align-items:center;background:#fff;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;padding:16px 25px;position:sticky;top:0;z-index:5}.search{background:var(--bg);border:1px solid var(--line);border-radius:9px;display:flex;gap:8px;padding:8px 12px;width:310px}.search input{background:none;border:0;outline:0;width:100%}.content{padding:24px}.panel,.metric{background:var(--card);border:1px solid var(--line);border-radius:14px;box-shadow:0 3px 15px rgba(16,29,43,.04)}.panel{padding:20px}.metric{display:block;padding:17px;text-decoration:none;transition:.2s}.metric:hover{box-shadow:0 12px 25px rgba(16,29,43,.1);transform:translateY(-3px)}.metric .icon{align-items:center;background:#e7f7f5;border-radius:11px;color:var(--teal);display:flex;font-size:20px;height:42px;justify-content:center;width:42px}.metric small{color:var(--muted);display:block;font-size:10px;font-weight:700;margin-top:12px;text-transform:uppercase}.metric strong{color:var(--ink);display:block;font-size:22px;margin-top:3px}.section-title{font-size:15px;font-weight:800}.sub{color:var(--muted);font-size:11px}.table{font-size:12px}.table th{color:var(--muted);font-size:9px;letter-spacing:.6px;text-transform:uppercase}.status{border-radius:20px;display:inline-block;font-size:9px;font-weight:800;padding:5px 9px;text-transform:uppercase}.s-pending{background:#fff3cd;color:#9a6700}.s-paid,.s-approved,.s-active{background:#d1fae5;color:#047857}.s-rejected,.s-failed,.s-cancelled{background:#fee2e2;color:#b91c1c}.s-expired{background:#e2e8f0;color:#475569}.s-available{background:#d1fae5;color:#047857}.s-booked{background:#dbeafe;color:#1d4ed8}.s-occupied{background:#ede9fe;color:#6d28d9}.property-img{border-radius:10px;height:70px;object-fit:cover;width:90px}.actions{display:flex;gap:5px}.chart-box{height:270px}.btn-teal{background:var(--teal);border-color:var(--teal);color:#fff}.btn-teal:hover{background:#0f766e;color:#fff}
    @media(max-width:900px){.side{transform:translateX(-100%)}.main{margin-left:0;width:100%}.search{width:180px}.content{padding:15px}}
  </style>
  @stack('css')
</head>
<body><div class="shell">
<aside class="side">
  <div class="brand"><i class="bi bi-buildings-fill"></i>KostKu</div>
  <div class="owner-meta"><small>Pemilik Terverifikasi</small><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->properties()->where('status','active')->count() }} properti aktif</small></div>
  <nav class="nav-links">
    <div class="label">Bisnis</div>
    @foreach([
      ['dashboard.owner','bi-grid-1x2-fill','Dashboard'],['owner.properties','bi-houses-fill','Properti Saya'],['owner.rooms','bi-door-open-fill','Kamar Kos'],
      ['owner.bookings','bi-calendar-check-fill','Booking Masuk'],['owner.tenants','bi-people-fill','Penyewa'],['owner.payments','bi-credit-card-fill','Pembayaran'],
      ['owner.revenue','bi-graph-up-arrow','Pendapatan'],['owner.reports','bi-file-earmark-bar-graph-fill','Laporan'],['owner.notifications','bi-bell-fill','Notifikasi']
    ] as [$route,$icon,$label])<a href="{{ route($route) }}" class="{{ request()->routeIs($route) ? 'active':'' }}"><i class="bi {{ $icon }}"></i>{{ $label }}</a>@endforeach
    <div class="label">Akun</div>
    <a href="{{ route('owner.profile') }}"><i class="bi bi-person-circle"></i>Profil</a><a href="{{ route('owner.settings') }}"><i class="bi bi-gear-fill"></i>Pengaturan</a>
  </nav>
  <div class="logout"><form action="{{ route('logout') }}" method="post">@csrf<button class="btn btn-outline-light btn-sm w-100"><i class="bi bi-box-arrow-right me-2"></i>Logout</button></form></div>
</aside>
<main class="main">
  <header class="top"><div><strong>@yield('heading','Owner Dashboard')</strong><div class="sub">{{ now()->locale('id_ID')->translatedFormat('l, d F Y') }}</div></div><form class="search" action="{{ route('owner.bookings') }}"><i class="bi bi-search"></i><input name="search" placeholder="Cari booking, penyewa..."></form></header>
  <div class="content">@foreach(['success','error'] as $type)@if(session($type))<div class="alert alert-{{ $type==='error'?'danger':'success' }}">{{ session($type) }}</div>@endif @endforeach @yield('content')</div>
</main></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script><script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('js')</body></html>
