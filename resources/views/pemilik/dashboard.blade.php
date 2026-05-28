<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>KostKu — Dashboard</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet"/>

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
      <a href="#" class="nav-item">
        <i class="bi bi-gear-fill"></i> Pengaturan
      </a>
    </nav>

    <!-- User -->
    <div class="sidebar-user">
      <img src="https://i.pravatar.cc/40?img=12" alt="avatar" class="user-avatar"/>
      <div class="user-info">
        <span class="user-name">Budi Santoso</span>
        <span class="user-role">Pemilik</span>
      </div>
      <i class="bi bi-box-arrow-right logout-icon"></i>
    </div>
  </aside>

  <!-- ===================== MAIN CONTENT ===================== -->
  <main class="main-content flex-grow-1">

    <!-- Topbar -->
    <header class="topbar d-flex align-items-center justify-content-between">
      <div>
        <h4 class="topbar-title">Selamat Datang, Budi 👋</h4>
        <p class="topbar-sub">Kamis, 29 Mei 2025 · Ringkasan properti Anda hari ini</p>
      </div>
      <div class="topbar-actions d-flex align-items-center gap-3">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Cari properti, penyewa…"/>
        </div>
        <button class="btn-icon" title="Notifikasi">
          <i class="bi bi-bell-fill"></i>
          <span class="notif-dot"></span>
        </button>
        <img src="https://i.pravatar.cc/36?img=12" class="topbar-avatar" alt="avatar"/>
      </div>
    </header>

    <!-- ===================== STAT CARDS ===================== -->
    <div class="content-body">
      <div class="row g-4 mb-4">
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-teal">
            <div class="stat-icon"><i class="bi bi-house-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Total Properti</span>
              <span class="stat-value">12</span>
              <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> 2 bulan ini</span>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-amber">
            <div class="stat-icon"><i class="bi bi-door-open-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Kamar Tersewa</span>
              <span class="stat-value">38<small>/47</small></span>
              <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> 81% terisi</span>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-green">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-info">
              <span class="stat-label">Pemasukan Bulan Ini</span>
              <span class="stat-value">Rp 48,5<small>jt</small></span>
              <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +12% vs bulan lalu</span>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-rose">
            <div class="stat-icon"><i class="bi bi-exclamation-circle-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Tunggakan</span>
              <span class="stat-value">5</span>
              <span class="stat-delta down"><i class="bi bi-arrow-down-short"></i> 3 jatuh tempo</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ===================== MID ROW ===================== -->
      <div class="row g-4 mb-4">

        <!-- Properti Overview -->
        <div class="col-xl-8">
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Daftar Properti</h6>
                <p class="panel-sub">Semua unit yang Anda kelola</p>
              </div>
              <button class="btn-add"><i class="bi bi-plus-lg"></i> Tambah Properti</button>
            </div>

            <div class="property-list">

              <!-- Item -->
              <div class="property-item">
                <div class="prop-thumb" style="background:#e0f2f1;">
                  <i class="bi bi-building" style="color:#0d9488;"></i>
                </div>
                <div class="prop-info">
                  <span class="prop-name">Kost Putri Melati</span>
                  <span class="prop-addr"><i class="bi bi-geo-alt-fill"></i> Jl. Kebon Jeruk No. 12, Jakarta Barat</span>
                </div>
                <div class="prop-stat">
                  <div class="occ-bar">
                    <div class="occ-fill" style="width:80%;background:#0d9488;"></div>
                  </div>
                  <span class="occ-text">8/10 terisi</span>
                </div>
                <span class="prop-badge badge-kost">Kost</span>
                <div class="prop-actions">
                  <button class="btn-ghost"><i class="bi bi-pencil"></i></button>
                  <button class="btn-ghost"><i class="bi bi-eye"></i></button>
                </div>
              </div>

              <div class="property-item">
                <div class="prop-thumb" style="background:#fef3c7;">
                  <i class="bi bi-houses" style="color:#d97706;"></i>
                </div>
                <div class="prop-info">
                  <span class="prop-name">Kontrakan Griya Asri</span>
                  <span class="prop-addr"><i class="bi bi-geo-alt-fill"></i> Jl. Cempaka Putih Tengah IV, Jakarta Pusat</span>
                </div>
                <div class="prop-stat">
                  <div class="occ-bar">
                    <div class="occ-fill" style="width:60%;background:#d97706;"></div>
                  </div>
                  <span class="occ-text">3/5 terisi</span>
                </div>
                <span class="prop-badge badge-kontrakan">Kontrakan</span>
                <div class="prop-actions">
                  <button class="btn-ghost"><i class="bi bi-pencil"></i></button>
                  <button class="btn-ghost"><i class="bi bi-eye"></i></button>
                </div>
              </div>

              <div class="property-item">
                <div class="prop-thumb" style="background:#ede9fe;">
                  <i class="bi bi-building-fill" style="color:#7c3aed;"></i>
                </div>
                <div class="prop-info">
                  <span class="prop-name">Kost Putra Bahagia</span>
                  <span class="prop-addr"><i class="bi bi-geo-alt-fill"></i> Jl. Margonda Raya No. 88, Depok</span>
                </div>
                <div class="prop-stat">
                  <div class="occ-bar">
                    <div class="occ-fill" style="width:93%;background:#7c3aed;"></div>
                  </div>
                  <span class="occ-text">14/15 terisi</span>
                </div>
                <span class="prop-badge badge-kost">Kost</span>
                <div class="prop-actions">
                  <button class="btn-ghost"><i class="bi bi-pencil"></i></button>
                  <button class="btn-ghost"><i class="bi bi-eye"></i></button>
                </div>
              </div>

              <div class="property-item">
                <div class="prop-thumb" style="background:#fce7f3;">
                  <i class="bi bi-house-heart-fill" style="color:#db2777;"></i>
                </div>
                <div class="prop-info">
                  <span class="prop-name">Kontrakan Villa Anggrek</span>
                  <span class="prop-addr"><i class="bi bi-geo-alt-fill"></i> Jl. Pondok Labu No. 5, Jakarta Selatan</span>
                </div>
                <div class="prop-stat">
                  <div class="occ-bar">
                    <div class="occ-fill" style="width:50%;background:#db2777;"></div>
                  </div>
                  <span class="occ-text">3/6 terisi</span>
                </div>
                <span class="prop-badge badge-kontrakan">Kontrakan</span>
                <div class="prop-actions">
                  <button class="btn-ghost"><i class="bi bi-pencil"></i></button>
                  <button class="btn-ghost"><i class="bi bi-eye"></i></button>
                </div>
              </div>

            </div>

            <a href="#" class="see-all">Lihat semua properti <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>

        <!-- Sidebar Right -->
        <div class="col-xl-4 d-flex flex-column gap-4">

          <!-- Kalender Jatuh Tempo -->
          <div class="panel flex-grow-1">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Jatuh Tempo</h6>
                <p class="panel-sub">Tagihan bulan ini</p>
              </div>
              <span class="chip chip-warning">3 Mendesak</span>
            </div>
            <div class="due-list">
              <div class="due-item">
                <div class="due-dot dot-red"></div>
                <div class="due-info">
                  <span class="due-name">Ahmad Fauzi</span>
                  <span class="due-unit">Kost Melati · Kamar 3A</span>
                </div>
                <div class="due-right">
                  <span class="due-amount">Rp 1.200.000</span>
                  <span class="due-date overdue">Terlambat 3 hari</span>
                </div>
              </div>
              <div class="due-item">
                <div class="due-dot dot-orange"></div>
                <div class="due-info">
                  <span class="due-name">Siti Rahayu</span>
                  <span class="due-unit">Kontrakan Griya · Unit B</span>
                </div>
                <div class="due-right">
                  <span class="due-amount">Rp 2.500.000</span>
                  <span class="due-date warning-date">Jatuh tempo besok</span>
                </div>
              </div>
              <div class="due-item">
                <div class="due-dot dot-orange"></div>
                <div class="due-info">
                  <span class="due-name">Rizky Pratama</span>
                  <span class="due-unit">Kost Bahagia · Kamar 7</span>
                </div>
                <div class="due-right">
                  <span class="due-amount">Rp 900.000</span>
                  <span class="due-date warning-date">Jatuh tempo besok</span>
                </div>
              </div>
              <div class="due-item">
                <div class="due-dot dot-green"></div>
                <div class="due-info">
                  <span class="due-name">Dewi Anggraeni</span>
                  <span class="due-unit">Kost Melati · Kamar 5B</span>
                </div>
                <div class="due-right">
                  <span class="due-amount">Rp 1.200.000</span>
                  <span class="due-date ok-date">3 Juni 2025</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Permintaan Pemeliharaan -->
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Pemeliharaan</h6>
                <p class="panel-sub">Permintaan aktif</p>
              </div>
              <span class="chip chip-danger">3 Baru</span>
            </div>
            <div class="maintain-list">
              <div class="maintain-item">
                <div class="maint-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-lightning-charge-fill"></i></div>
                <div class="maint-info">
                  <span class="maint-title">Listrik mati — Kamar 2A</span>
                  <span class="maint-loc">Kost Melati</span>
                </div>
                <span class="maint-status status-open">Baru</span>
              </div>
              <div class="maintain-item">
                <div class="maint-icon" style="background:#e0f2f1;color:#0d9488;"><i class="bi bi-droplet-fill"></i></div>
                <div class="maint-info">
                  <span class="maint-title">Pipa bocor — Kamar Mandi</span>
                  <span class="maint-loc">Kontrakan Griya · Unit C</span>
                </div>
                <span class="maint-status status-progress">Proses</span>
              </div>
              <div class="maintain-item">
                <div class="maint-icon" style="background:#ede9fe;color:#7c3aed;"><i class="bi bi-door-closed-fill"></i></div>
                <div class="maint-info">
                  <span class="maint-title">Pintu rusak — Kamar 7</span>
                  <span class="maint-loc">Kost Bahagia</span>
                </div>
                <span class="maint-status status-open">Baru</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- ===================== BOTTOM ROW ===================== -->
      <div class="row g-4">

        <!-- Penyewa Terbaru -->
        <div class="col-xl-7">
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Penyewa Terbaru</h6>
                <p class="panel-sub">5 penyewa yang baru bergabung</p>
              </div>
              <a href="#" class="see-all-link">Lihat semua</a>
            </div>
            <div class="table-responsive">
              <table class="table tenant-table">
                <thead>
                  <tr>
                    <th>Penyewa</th>
                    <th>Unit</th>
                    <th>Sewa/Bulan</th>
                    <th>Kontrak</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <img src="https://i.pravatar.cc/32?img=5" class="tenant-pic" alt=""/>
                        <div>
                          <span class="t-name">Rina Kusuma</span>
                          <span class="t-phone">+62 812-xxxx-1234</span>
                        </div>
                      </div>
                    </td>
                    <td><span class="unit-tag">Melati · 4A</span></td>
                    <td class="fw-600">Rp 1.200.000</td>
                    <td class="text-muted small">01 Mei — 30 Apr '26</td>
                    <td><span class="status-pill pill-active">Aktif</span></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <img src="https://i.pravatar.cc/32?img=8" class="tenant-pic" alt=""/>
                        <div>
                          <span class="t-name">Dimas Aditya</span>
                          <span class="t-phone">+62 858-xxxx-5678</span>
                        </div>
                      </div>
                    </td>
                    <td><span class="unit-tag">Bahagia · 11</span></td>
                    <td class="fw-600">Rp 900.000</td>
                    <td class="text-muted small">15 Apr — 14 Apr '26</td>
                    <td><span class="status-pill pill-active">Aktif</span></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <img src="https://i.pravatar.cc/32?img=15" class="tenant-pic" alt=""/>
                        <div>
                          <span class="t-name">Mira Handayani</span>
                          <span class="t-phone">+62 821-xxxx-9012</span>
                        </div>
                      </div>
                    </td>
                    <td><span class="unit-tag">Griya · Unit A</span></td>
                    <td class="fw-600">Rp 2.500.000</td>
                    <td class="text-muted small">01 Apr — 31 Mar '26</td>
                    <td><span class="status-pill pill-warning">Perlu Bayar</span></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <img src="https://i.pravatar.cc/32?img=22" class="tenant-pic" alt=""/>
                        <div>
                          <span class="t-name">Hendra Wijaya</span>
                          <span class="t-phone">+62 877-xxxx-3456</span>
                        </div>
                      </div>
                    </td>
                    <td><span class="unit-tag">Anggrek · Unit 2</span></td>
                    <td class="fw-600">Rp 2.000.000</td>
                    <td class="text-muted small">20 Mar — 19 Mar '26</td>
                    <td><span class="status-pill pill-active">Aktif</span></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <img src="https://i.pravatar.cc/32?img=30" class="tenant-pic" alt=""/>
                        <div>
                          <span class="t-name">Yanti Saputri</span>
                          <span class="t-phone">+62 813-xxxx-7890</span>
                        </div>
                      </div>
                    </td>
                    <td><span class="unit-tag">Melati · 2B</span></td>
                    <td class="fw-600">Rp 1.200.000</td>
                    <td class="text-muted small">10 Mar — 09 Mar '26</td>
                    <td><span class="status-pill pill-danger">Terlambat</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Pemasukan Chart (simple bars) -->
        <div class="col-xl-5">
          <div class="panel h-100">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Pemasukan Bulanan</h6>
                <p class="panel-sub">Jan — Jun 2025</p>
              </div>
              <span class="chip chip-success">+12% <i class="bi bi-arrow-up-short"></i></span>
            </div>
            <div class="chart-wrap">
              <div class="bar-chart">
                <div class="bar-col">
                  <div class="bar-fill" style="height:55%;" title="Rp 28jt"><span class="bar-tip">28jt</span></div>
                  <span class="bar-label">Jan</span>
                </div>
                <div class="bar-col">
                  <div class="bar-fill" style="height:65%;" title="Rp 32jt"><span class="bar-tip">32jt</span></div>
                  <span class="bar-label">Feb</span>
                </div>
                <div class="bar-col">
                  <div class="bar-fill" style="height:72%;" title="Rp 38jt"><span class="bar-tip">38jt</span></div>
                  <span class="bar-label">Mar</span>
                </div>
                <div class="bar-col">
                  <div class="bar-fill" style="height:68%;" title="Rp 35jt"><span class="bar-tip">35jt</span></div>
                  <span class="bar-label">Apr</span>
                </div>
                <div class="bar-col">
                  <div class="bar-fill" style="height:83%;" title="Rp 43jt"><span class="bar-tip">43jt</span></div>
                  <span class="bar-label">Mei</span>
                </div>
                <div class="bar-col active">
                  <div class="bar-fill bar-active" style="height:93%;" title="Rp 48,5jt"><span class="bar-tip">48,5jt</span></div>
                  <span class="bar-label bar-label-active">Jun</span>
                </div>
              </div>
              <div class="chart-summary">
                <div class="cs-item">
                  <span class="cs-dot" style="background:#0d9488;"></span>
                  <span>Kost</span>
                  <strong>Rp 31,2jt</strong>
                </div>
                <div class="cs-item">
                  <span class="cs-dot" style="background:#f59e0b;"></span>
                  <span>Kontrakan</span>
                  <strong>Rp 17,3jt</strong>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- end bottom row -->

    </div><!-- end content-body -->
  </main>
</div><!-- end wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>