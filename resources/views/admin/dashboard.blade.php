<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin — KostKu</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  @vite(['resources/css/style.css'])
</head>
<body>
<div class="wrapper d-flex">
  <aside class="sidebar d-flex flex-column">
    <div class="sidebar-brand">
      <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
      <div>
        <span class="brand-name">KostKu</span>
        <span class="brand-sub">Admin Panel</span>
      </div>
    </div>
    <nav class="sidebar-nav flex-grow-1">
      <p class="nav-label">Menu Admin</p>
      <a href="/admin" class="nav-item active"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a href="#" class="nav-item"><i class="bi bi-people-fill"></i> Kelola Pengguna</a>
      <a href="#" class="nav-item"><i class="bi bi-building-fill"></i> Kelola Properti</a>
      <a href="#" class="nav-item"><i class="bi bi-cash-stack"></i> Kelola Pembayaran</a>
      <a href="#" class="nav-item"><i class="bi bi-gear-fill"></i> Pengaturan Sistem</a>
      <p class="nav-label mt-3">Laporan</p>
      <a href="#" class="nav-item"><i class="bi bi-file-earmark-text-fill"></i> Laporan Bulanan</a>
      <a href="#" class="nav-item"><i class="bi bi-bar-chart-line-fill"></i> Analitik</a>
    </nav>
    <div class="sidebar-user">
      <img src="https://i.pravatar.cc/40?img=18" alt="avatar" class="user-avatar"/>
      <div class="user-info">
        <span class="user-name">Admin KostKu</span>
        <span class="user-role">Administrator</span>
      </div>
      <i class="bi bi-box-arrow-right logout-icon"></i>
    </div>
  </aside>

  <main class="main-content flex-grow-1">
    <header class="topbar d-flex align-items-center justify-content-between">
      <div>
        <h4 class="topbar-title">Halo, Admin 👋</h4>
        <p class="topbar-sub">Dashboard seluruh sistem KostKu</p>
      </div>
      <div class="topbar-actions d-flex align-items-center gap-3">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Cari laporan atau pengguna…"/>
        </div>
        <button class="btn-icon" title="Notifikasi"><i class="bi bi-bell-fill"></i><span class="notif-dot"></span></button>
        <img src="https://i.pravatar.cc/36?img=18" class="topbar-avatar" alt="avatar"/>
      </div>
    </header>

    <div class="content-body">
      <div class="row g-4 mb-4">
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-teal">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Total Pengguna</span>
              <span class="stat-value">128</span>
              <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +5% bulan ini</span>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-amber">
            <div class="stat-icon"><i class="bi bi-building-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Total Properti</span>
              <span class="stat-value">24</span>
              <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +2 properti</span>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-green">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-info">
              <span class="stat-label">Pendapatan</span>
              <span class="stat-value">Rp 112,3<small>jt</small></span>
              <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +18% vs bulan lalu</span>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-rose">
            <div class="stat-icon"><i class="bi bi-exclamation-circle-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Permintaan Support</span>
              <span class="stat-value">7</span>
              <span class="stat-delta down"><i class="bi bi-arrow-down-short"></i> 3 belum selesai</span>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-xl-8">
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Kontrol Harga Kos</h6>
                <p class="panel-sub">Atur harga sewa per properti</p>
              </div>
              <button class="btn-add"><i class="bi bi-pencil-fill"></i> Atur Harga</button>
            </div>
            <div class="price-control">
              <div class="table-responsive">
                <table class="table table-borderless">
                  <thead>
                    <tr>
                      <th>Properti</th>
                      <th>Type</th>
                      <th>Harga Saat Ini</th>
                      <th>Status</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Kost Putri Melati</td>
                      <td>Kost Putri</td>
                      <td>Rp 1.200.000</td>
                      <td><span class="badge bg-success">Aktif</span></td>
                      <td><button class="btn btn-sm btn-outline-primary">Ubah</button></td>
                    </tr>
                    <tr>
                      <td>Kontrakan Griya Asri</td>
                      <td>Kontrakan</td>
                      <td>Rp 2.500.000</td>
                      <td><span class="badge bg-warning">Review</span></td>
                      <td><button class="btn btn-sm btn-outline-primary">Ubah</button></td>
                    </tr>
                    <tr>
                      <td>Kost Putra Bahagia</td>
                      <td>Kost Putra</td>
                      <td>Rp 1.350.000</td>
                      <td><span class="badge bg-success">Aktif</span></td>
                      <td><button class="btn btn-sm btn-outline-primary">Ubah</button></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-4 d-flex flex-column gap-4">
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Review Admin</h6>
                <p class="panel-sub">Pengaturan cepat</p>
              </div>
            </div>
            <div class="admin-shortcuts">
              <a href="#" class="action-btn action-primary">
                <div class="action-icon"><i class="bi bi-person-lines-fill"></i></div>
                <div class="action-info">
                  <span class="action-label">Tambah Pengguna</span>
                  <span class="action-desc">Buat akun baru</span>
                </div>
                <i class="bi bi-chevron-right"></i>
              </a>
              <a href="#" class="action-btn action-secondary">
                <div class="action-icon"><i class="bi bi-bell-fill"></i></div>
                <div class="action-info">
                  <span class="action-label">Audit Log</span>
                  <span class="action-desc">Lihat aktivitas sistem</span>
                </div>
                <i class="bi bi-chevron-right"></i>
              </a>
              <a href="#" class="action-btn">
                <div class="action-icon"><i class="bi bi-graph-up"></i></div>
                <div class="action-info">
                  <span class="action-label">Ringkasan Laporan</span>
                  <span class="action-desc">Cek statistik cepat</span>
                </div>
                <i class="bi bi-chevron-right"></i>
              </a>
            </div>
          </div>
          <div class="panel">
            <div class="panel-header">
              <h6 class="panel-title">Pengumuman Sistem</h6>
            </div>
            <div class="announcements">
              <div class="announcement-item">
                <span class="ann-date">1 Juni</span>
                <p class="ann-text">Pembayaran otomatis ditambahkan dalam sistem minggu depan.</p>
              </div>
              <div class="announcement-item">
                <span class="ann-date">25 Mei</span>
                <p class="ann-text">Pembaruan keamanan dashboard dijadwalkan pada malam hari.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
