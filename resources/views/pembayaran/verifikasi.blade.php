<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KostKu — Verifikasi Pembayaran</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />

  @vite(['resources/css/style.css'])
</head>
<body>
<div class="wrapper d-flex">
  <aside class="sidebar d-flex flex-column">
    <div class="sidebar-brand">
      <div class="brand-icon"><i class="bi bi-buildings-fill"></i></div>
      <div>
        <span class="brand-name">KostKu</span>
        <span class="brand-sub">Property Manager</span>
      </div>
    </div>

    <nav class="sidebar-nav flex-grow-1">
      <p class="nav-label">Menu Utama</p>
      <a href="/" class="nav-item">
        <i class="bi bi-house-door-fill"></i> Beranda
      </a>
      <a href="{{ route('pembayaran.upload') }}" class="nav-item active">
        <i class="bi bi-cash-coin"></i> Pembayaran
      </a>


      <p class="nav-label mt-3">Operasional</p>
      <a href="#" class="nav-item">
        <i class="bi bi-file-earmark-text-fill"></i> Kontrak
      </a>
      <a href="#" class="nav-item">
        <i class="bi bi-megaphone-fill"></i> Pengumuman
      </a>

      <p class="nav-label mt-3">Akun</p>
      <a href="#" class="nav-item">
        <i class="bi bi-gear-fill"></i> Pengaturan
      </a>
    </nav>

    <div class="sidebar-user">
      <img src="https://i.pravatar.cc/40?img=12" alt="avatar" class="user-avatar" />
      <div class="user-info">
        <span class="user-name">Budi Santoso</span>
        <span class="user-role">Pemilik</span>
      </div>
      <i class="bi bi-box-arrow-right logout-icon"></i>
    </div>
  </aside>

  <main class="main-content flex-grow-1">
    <header class="topbar d-flex align-items-center justify-content-between">
      <div>
        <h4 class="topbar-title">Verifikasi Pembayaran</h4>
        <p class="topbar-sub">Periksa dan kelola bukti pembayaran yang masuk.</p>
      </div>
      <div class="topbar-actions d-flex align-items-center gap-3">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Cari bukti…" />
        </div>
        <button class="btn-icon" title="Notifikasi">
          <i class="bi bi-bell-fill"></i>
          <span class="notif-dot"></span>
        </button>
        <img src="https://i.pravatar.cc/36?img=12" class="topbar-avatar" alt="avatar" />
      </div>
    </header>

    <div class="content-body">
      <div class="row g-4 mb-4">
        <div class="col-xl-12">
          <div class="panel d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
            <div>
              <h6 class="panel-title">Daftar Verifikasi Pembayaran</h6>
              <p class="panel-sub">Kelola bukti pembayaran penyewa dan perbarui status transaksi.</p>
            </div>
            <a href="{{ route('pembayaran.upload') }}" class="see-all-link">Kembali ke Upload</a>
          </div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-sm-4">
          <div class="stat-card card-amber">
            <div class="stat-icon"><i class="bi bi-clock-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Menunggu</span>
              <span class="stat-value">{{ $payments->where('status', App\Models\Payment::STATUS_PENDING)->count() }}</span>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="stat-card card-teal">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Terverifikasi</span>
              <span class="stat-value">{{ $payments->where('status', App\Models\Payment::STATUS_VERIFIED)->count() }}</span>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="stat-card card-rose">
            <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Ditolak</span>
              <span class="stat-value">{{ $payments->where('status', App\Models\Payment::STATUS_REJECTED)->count() }}</span>
            </div>
          </div>
        </div>
      </div>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="panel">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Nama Penyewa</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($payments as $payment)
                <tr>
                  <td>{{ $payment->id }}</td>
                  <td>{{ $payment->tenant_name }}</td>
                  <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                  <td>{{ $payment->payment_date->format('d M Y') }}</td>
                  <td>
                    <span class="badge bg-{{ $payment->status === App\Models\Payment::STATUS_VERIFIED ? 'success' : ($payment->status === App\Models\Payment::STATUS_REJECTED ? 'danger' : 'secondary') }}">
                      {{ ucfirst($payment->status) }}
                    </span>
                  </td>
                  <td>{{ $payment->notes ?? '-' }}</td>
                  <td class="text-nowrap">
                    <a href="{{ asset($payment->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-info mb-1">Bukti</a>
                    <form action="{{ route('pembayaran.verify', $payment) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="action" value="verified" />
                      <button type="submit" class="btn btn-sm btn-success mb-1">Verifikasi</button>
                    </form>
                    <form action="{{ route('pembayaran.verify', $payment) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="action" value="rejected" />
                      <button type="submit" class="btn btn-sm btn-danger mb-1">Tolak</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4">Belum ada pembayaran yang perlu diverifikasi.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</div>
</body>
</html>
