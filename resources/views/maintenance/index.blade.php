<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KostKu — Komplain Maintenance</title>

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
      <a href="{{ route('maintenance.index') }}" class="nav-item active">
        <i class="bi bi-wrench-adjustable-circle-fill"></i> Maintenance
      </a>

      <p class="nav-label mt-3">Operasional</p>
      <a href="{{ route('maintenance.index') }}" class="nav-item">
        <i class="bi bi-clipboard-check-fill"></i> Komplain Saya
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
        <span class="user-name">Penyewa</span>
        <span class="user-role">Pengguna</span>
      </div>
      <i class="bi bi-box-arrow-right logout-icon"></i>
    </div>
  </aside>

  <main class="main-content flex-grow-1">
    <header class="topbar d-flex align-items-center justify-content-between">
      <div>
        <h4 class="topbar-title">Komplain Maintenance</h4>
        <p class="topbar-sub">Laporkan kerusakan dan pantau status tindak lanjut.</p>
      </div>
      <div class="topbar-actions d-flex align-items-center gap-3">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Cari komplain…" />
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
        <div class="col-xl-6">
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Buat Komplain Baru</h6>
                <p class="panel-sub">Isi detail masalah agar pemilik dapat segera menindaklanjuti.</p>
              </div>
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

            <form action="{{ route('maintenance.store') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label class="form-label" for="tenant_name">Nama Penyewa</label>
                <input type="text" class="form-control" id="tenant_name" name="tenant_name" value="{{ old('tenant_name') }}" required />
              </div>

              <div class="mb-3">
                <label class="form-label" for="room_number">Nomor/Kamar</label>
                <input type="text" class="form-control" id="room_number" name="room_number" value="{{ old('room_number') }}" placeholder="Opsional" />
              </div>

              <div class="mb-3">
                <label class="form-label" for="category">Kategori</label>
                <select class="form-select" id="category" name="category" required>
                  <option value="">Pilih kategori</option>
                  <option value="Listrik" {{ old('category') == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                  <option value="Air" {{ old('category') == 'Air' ? 'selected' : '' }}>Air</option>
                  <option value="Kebersihan" {{ old('category') == 'Kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                  <option value="Struktur" {{ old('category') == 'Struktur' ? 'selected' : '' }}>Struktur</option>
                  <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label" for="description">Deskripsi Komplain</label>
                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
              </div>

              <button type="submit" class="btn-add">Kirim Komplain</button>
            </form>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Ringkasan Status</h6>
                <p class="panel-sub">Pantau perkembangan komplain Anda dalam satu tampilan.</p>
              </div>
              <a href="{{ route('pemilik.maintenance') }}" class="see-all-link">Lihat Manajemen</a>
            </div>

            <div class="row g-3">
              <div class="col-sm-6">
                <div class="stat-card card-amber">
                  <div class="stat-icon"><i class="bi bi-clock-fill"></i></div>
                  <div class="stat-info">
                    <span class="stat-label">Baru</span>
                    <span class="stat-value">{{ $maintenances->where('status', App\Models\Maintenance::STATUS_NEW)->count() }}</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="stat-card card-teal">
                  <div class="stat-icon"><i class="bi bi-gear-fill"></i></div>
                  <div class="stat-info">
                    <span class="stat-label">Sedang Diproses</span>
                    <span class="stat-value">{{ $maintenances->where('status', App\Models\Maintenance::STATUS_IN_PROGRESS)->count() }}</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="stat-card card-green">
                  <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                  <div class="stat-info">
                    <span class="stat-label">Selesai</span>
                    <span class="stat-value">{{ $maintenances->where('status', App\Models\Maintenance::STATUS_RESOLVED)->count() }}</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="stat-card card-rose">
                  <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
                  <div class="stat-info">
                    <span class="stat-label">Ditolak</span>
                    <span class="stat-value">{{ $maintenances->where('status', App\Models\Maintenance::STATUS_REJECTED)->count() }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel">
        <div class="panel-header">
          <div>
            <h6 class="panel-title">Riwayat Komplain</h6>
            <p class="panel-sub">Semua laporan maintenance yang pernah Anda kirim.</p>
          </div>
        </div>

        @if($maintenances->isEmpty())
          <div class="alert alert-info mb-0">Belum ada komplain yang dikirim.</div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Nama</th>
                  <th>Kamar</th>
                  <th>Kategori</th>
                  <th>Deskripsi</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($maintenances as $maintenance)
                  <tr>
                    <td>{{ $maintenance->created_at->format('d-m-Y H:i') }}</td>
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
