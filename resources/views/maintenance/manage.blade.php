<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KostKu — Manajemen Maintenance</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
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
      <a href="{{ route('pembayaran.upload') }}" class="nav-item">
        <i class="bi bi-cash-coin"></i> Pembayaran
      </a>
      <a href="{{ route('pemilik.maintenance') }}" class="nav-item active">
        <i class="bi bi-wrench-adjustable-circle-fill"></i> Maintenance
      </a>

      <p class="nav-label mt-3">Operasional</p>
      <a href="{{ route('pemilik.maintenance') }}" class="nav-item">
        <i class="bi bi-clipboard-check-fill"></i> Komplain
      </a>
      <a href="#" class="nav-item">
        <i class="bi bi-megaphone-fill"></i> Pengumuman
      </a>
      <a href="#" class="nav-item">
        <i class="bi bi-bar-chart-fill"></i> Laporan
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
        <h4 class="topbar-title">Halaman Pemilik - Maintenance</h4>
        <p class="topbar-sub">Kelola semua laporan komplain dan update status perbaikan.</p>
      </div>
      <div class="topbar-actions d-flex align-items-center gap-3">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Cari laporan…" />
        </div>
        <button class="btn-icon" title="Notifikasi">
          <i class="bi bi-bell-fill"></i>
          <span class="notif-dot"></span>
        </button>
        <img src="https://i.pravatar.cc/36?img=12" class="topbar-avatar" alt="avatar" />
      </div>
    </header>

    <div class="content-body">
      <div class="panel mb-4">
        <div class="panel-header">
          <div>
            <h6 class="panel-title">Daftar Maintenance</h6>
            <p class="panel-sub">Lihat semua komplain pengguna dan beri status akhir.</p>
          </div>
          <a href="{{ route('maintenance.index') }}" class="see-all-link">Halaman Pengguna</a>
        </div>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
          <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if($maintenances->isEmpty())
          <div class="alert alert-info mb-0">Belum ada laporan maintenance.</div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama</th>
                  <th>Kamar</th>
                  <th>Kategori</th>
                  <th>Deskripsi</th>
                  <th>Status</th>
                  <th>Catatan Pemilik</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($maintenances as $maintenance)
                  <tr>
                    <td>{{ $maintenance->id }}</td>
                    <td>{{ $maintenance->tenant_name }}</td>
                    <td>{{ $maintenance->room_number ?: '-' }}</td>
                    <td>{{ $maintenance->category ?: '-' }}</td>
                    <td>{{ $maintenance->description }}</td>
                    <td>
                      @php
                        $statusClass = match($maintenance->status) {
                          App\Models\Maintenance::STATUS_NEW => 'chip-warning',
                          App\Models\Maintenance::STATUS_IN_PROGRESS => 'chip',
                          App\Models\Maintenance::STATUS_RESOLVED => 'chip-success',
                          App\Models\Maintenance::STATUS_REJECTED => 'chip-danger',
                          default => 'chip-warning',
                        };
                      @endphp
                      <span class="chip {{ $statusClass }}">{{ $maintenance->status_label }}</span>
                    </td>
                    <td>{{ $maintenance->owner_notes ?: '-' }}</td>
                    <td class="text-nowrap" style="min-width:230px;">
                      <form action="{{ route('pemilik.maintenance.status', $maintenance) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                          <select class="form-select form-select-sm" id="action_{{ $maintenance->id }}" name="action" required>
                            <option value="">Pilih status</option>
                            <option value="{{ App\Models\Maintenance::STATUS_IN_PROGRESS }}">Sedang Diproses</option>
                            <option value="{{ App\Models\Maintenance::STATUS_RESOLVED }}">Selesai</option>
                            <option value="{{ App\Models\Maintenance::STATUS_REJECTED }}">Ditolak</option>
                          </select>
                        </div>
                        <div class="mb-2">
                          <textarea class="form-control form-control-sm" name="owner_notes" rows="2" placeholder="Catatan opsional"></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </main>
</div>

</body>
</html>
