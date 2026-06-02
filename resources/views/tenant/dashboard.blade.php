<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Tenant — KostKu</title>

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
      <div class="brand-icon"><i class="bi bi-door-open-fill"></i></div>
      <div>
        <span class="brand-name">KostKu</span>
        <span class="brand-sub">Tenant Portal</span>
      </div>
    </div>

    <!-- Nav -->
    <nav class="sidebar-nav flex-grow-1">
      <p class="nav-label">Menu</p>
      <a href="/tenant" class="nav-item active">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
      </a>
      <a href="#" class="nav-item">
        <i class="bi bi-door-open-fill"></i> Kontrak Saya
      </a>
      <a href="/pembayaran" class="nav-item">
        <i class="bi bi-cash-coin"></i> Pembayaran
      </a>
      <a href="#" class="nav-item">
        <i class="bi bi-chat-dots-fill"></i> Pesan/Support
        <span class="badge-nav">2</span>
      </a>

      <p class="nav-label mt-3">Informasi</p>
      <a href="#" class="nav-item">
        <i class="bi bi-info-circle-fill"></i> Peraturan
      </a>
      <a href="#" class="nav-item">
        <i class="bi bi-calendar-event-fill"></i> Pengumuman
      </a>

      <p class="nav-label mt-3">Akun</p>
      <a href="#" class="nav-item">
        <i class="bi bi-person-fill"></i> Profil
      </a>
      <a href="#" class="nav-item">
        <i class="bi bi-gear-fill"></i> Pengaturan
      </a>
    </nav>

    <!-- User -->
    <div class="sidebar-user">
      <img src="https://i.pravatar.cc/40?img=3" alt="avatar" class="user-avatar"/>
      <div class="user-info">
        <span class="user-name">Ahmad Fauzi</span>
        <span class="user-role">Penyewa</span>
      </div>
      <i class="bi bi-box-arrow-right logout-icon"></i>
    </div>
  </aside>

  <!-- ===================== MAIN CONTENT ===================== -->
  <main class="main-content flex-grow-1">

    <!-- Topbar -->
    <header class="topbar d-flex align-items-center justify-content-between">
      <div>
        <h4 class="topbar-title">Halo, Ahmad 👋</h4>
        <p class="topbar-sub">Kamis, 29 Mei 2025 · Informasi kontrak dan pembayaran Anda</p>
      </div>
      <div class="topbar-actions d-flex align-items-center gap-3">
        <button class="btn-icon" title="Notifikasi">
          <i class="bi bi-bell-fill"></i>
          <span class="notif-dot"></span>
        </button>
        <img src="https://i.pravatar.cc/36?img=3" class="topbar-avatar" alt="avatar"/>
      </div>
    </header>

    <!-- ===================== STAT CARDS ===================== -->
    <div class="content-body">
      <div class="row g-4 mb-4">
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-teal">
            <div class="stat-icon"><i class="bi bi-door-open-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Unit Saya</span>
              <span class="stat-value">Kamar 3A</span>
              <span class="stat-delta ok"><i class="bi bi-check-circle-fill"></i> Aktif</span>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-amber">
            <div class="stat-icon"><i class="bi bi-calendar-check-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Status Kontrak</span>
              <span class="stat-value">Aktif</span>
              <span class="stat-delta ok"><i class="bi bi-arrow-up-short"></i> Hingga Juni 2026</span>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-green">
            <div class="stat-icon"><i class="bi bi-credit-card-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Pembayaran Bulan Ini</span>
              <span class="stat-value">Rp 1,2<small>jt</small></span>
              <span class="stat-delta ok"><i class="bi bi-check-circle-fill"></i> Lunas</span>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="stat-card card-rose">
            <div class="stat-icon"><i class="bi bi-exclamation-circle-fill"></i></div>
            <div class="stat-info">
              <span class="stat-label">Tunggakan</span>
              <span class="stat-value">0</span>
              <span class="stat-delta ok"><i class="bi bi-check-circle-fill"></i> Lancar</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ===================== MAIN CONTENT ROW ===================== -->
      <div class="row g-4 mb-4">

        <!-- Info Kontrak & Pembayaran -->
        <div class="col-xl-8">
          <!-- Informasi Kontrak -->
          <div class="panel mb-4">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Informasi Kontrak</h6>
                <p class="panel-sub">Detail kontrak sewa Anda</p>
              </div>
              <button class="btn-add btn-sm"><i class="bi bi-download"></i> Download</button>
            </div>

            <div class="contract-info">
              <div class="info-grid">
                <div class="info-item">
                  <span class="info-label">Properti</span>
                  <span class="info-value">Kost Putri Melati</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Lokasi</span>
                  <span class="info-value">Jl. Kebon Jeruk No. 12, Jakarta Barat</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Unit/Kamar</span>
                  <span class="info-value">Kamar 3A</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Tipe</span>
                  <span class="info-value">Kost Putri</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Tanggal Mulai</span>
                  <span class="info-value">1 Juni 2024</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Tanggal Berakhir</span>
                  <span class="info-value">31 Mei 2026</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Harga Sewa/Bulan</span>
                  <span class="info-value fw-600">Rp 1.200.000</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Status</span>
                  <span class="badge-inline badge-success">Aktif</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Riwayat Pembayaran -->
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Riwayat Pembayaran</h6>
                <p class="panel-sub">5 pembayaran terakhir</p>
              </div>
              <a href="/pembayaran" class="btn-add btn-sm"><i class="bi bi-eye"></i> Lihat Semua</a>
            </div>

            <div class="payment-history">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Periode</th>
                    <th>Jumlah</th>
                    <th>Tanggal Upload</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Mei 2025</td>
                    <td>Rp 1.200.000</td>
                    <td>28 Mei 2025</td>
                    <td><span class="badge bg-success">Lunas</span></td>
                  </tr>
                  <tr>
                    <td>April 2025</td>
                    <td>Rp 1.200.000</td>
                    <td>27 April 2025</td>
                    <td><span class="badge bg-success">Lunas</span></td>
                  </tr>
                  <tr>
                    <td>Maret 2025</td>
                    <td>Rp 1.200.000</td>
                    <td>29 Maret 2025</td>
                    <td><span class="badge bg-success">Lunas</span></td>
                  </tr>
                  <tr>
                    <td>Februari 2025</td>
                    <td>Rp 1.200.000</td>
                    <td>26 Februari 2025</td>
                    <td><span class="badge bg-warning">Verifikasi</span></td>
                  </tr>
                  <tr>
                    <td>Januari 2025</td>
                    <td>Rp 1.200.000</td>
                    <td>31 Januari 2025</td>
                    <td><span class="badge bg-success">Lunas</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Sidebar Right -->
        <div class="col-xl-4 d-flex flex-column gap-4">

          <!-- Aksi Cepat -->
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Aksi Cepat</h6>
                <p class="panel-sub">Kelola akun Anda</p>
              </div>
            </div>
            <div class="quick-actions">
              <a href="/pembayaran" class="action-btn action-primary">
                <div class="action-icon"><i class="bi bi-cash-coin"></i></div>
                <div class="action-info">
                  <span class="action-label">Kirim Pembayaran</span>
                  <span class="action-desc">Upload bukti transfer</span>
                </div>
                <i class="bi bi-chevron-right"></i>
              </a>
              <a href="#" class="action-btn action-secondary">
                <div class="action-icon"><i class="bi bi-tools"></i></div>
                <div class="action-info">
                  <span class="action-label">Lapor Pemeliharaan</span>
                  <span class="action-desc">Buat tiket service</span>
                </div>
                <i class="bi bi-chevron-right"></i>
              </a>
              <a href="#" class="action-btn">
                <div class="action-icon"><i class="bi bi-chat-dots"></i></div>
                <div class="action-info">
                  <span class="action-label">Hubungi Support</span>
                  <span class="action-desc">Kirim pertanyaan</span>
                </div>
                <i class="bi bi-chevron-right"></i>
              </a>
              <a href="#" class="action-btn">
                <div class="action-icon"><i class="bi bi-person"></i></div>
                <div class="action-info">
                  <span class="action-label">Ubah Profil</span>
                  <span class="action-desc">Perbarui data pribadi</span>
                </div>
                <i class="bi bi-chevron-right"></i>
              </a>
            </div>
          </div>

          <!-- Info Penting -->
          <div class="panel">
            <div class="panel-header">
              <h6 class="panel-title">Info Penting</h6>
            </div>
            <div class="important-info">
              <div class="info-box info-alert">
                <i class="bi bi-info-circle-fill"></i>
                <div>
                  <p class="info-title">Jadwal Pembayaran</p>
                  <p class="info-text">Pembayaran harus dilakukan sebelum tanggal 30 setiap bulannya.</p>
                </div>
              </div>
              <div class="info-box info-warning">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                  <p class="info-title">Terlambat 3 Hari</p>
                  <p class="info-text">Kontrak akan otomatis terputus jika tunggakan lebih dari 3 bulan.</p>
                </div>
              </div>
              <div class="info-box info-success">
                <i class="bi bi-check-circle-fill"></i>
                <div>
                  <p class="info-title">Update Terbaru</p>
                  <p class="info-text">Sistem pembayaran online sudah tersedia. Gunakan fitur ini untuk kemudahan.</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Pengumuman -->
          <div class="panel">
            <div class="panel-header">
              <h6 class="panel-title">Pengumuman Terbaru</h6>
            </div>
            <div class="announcements">
              <div class="announcement-item">
                <span class="ann-date">29 Mei</span>
                <p class="ann-text">Air mati untuk maintenance pada tanggal 1-2 Juni pukul 08:00-12:00</p>
              </div>
              <div class="announcement-item">
                <span class="ann-date">25 Mei</span>
                <p class="ann-text">Kebijakan baru tentang tamu. Silakan baca di halaman peraturan.</p>
              </div>
              <div class="announcement-item">
                <span class="ann-date">20 Mei</span>
                <p class="ann-text">Pemeliharaan AC dilakukan secara berkala setiap minggu.</p>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>

  </main>

</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* Additional Tenant Dashboard Styles */
.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  padding: 20px 0;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.info-label {
  font-size: 12px;
  color: var(--text-secondary);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-weight: 500;
}

.info-value {
  font-size: 14px;
  color: var(--text-primary);
  font-weight: 500;
}

.badge-inline {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.badge-success {
  background: var(--green-light);
  color: var(--green);
}

.payment-history table {
  margin-bottom: 0;
}

.payment-history th {
  border-top: 1px solid var(--border);
  border-bottom: 2px solid var(--border);
  color: var(--text-secondary);
  font-weight: 500;
  font-size: 12px;
  text-transform: uppercase;
  padding: 12px;
}

.payment-history td {
  border-bottom: 1px solid var(--border);
  padding: 14px 12px;
  color: var(--text-primary);
  font-size: 13px;
}

.payment-history tr:last-child td {
  border-bottom: none;
}

.quick-actions {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  border-radius: var(--radius-sm);
  border: 1px solid var(--border);
  background: transparent;
  text-decoration: none;
  color: inherit;
  transition: all 0.2s ease;
}

.action-btn:hover {
  background: var(--teal-light);
  border-color: var(--teal);
}

.action-btn.action-primary {
  background: var(--teal-light);
  border-color: var(--teal);
}

.action-btn.action-secondary {
  background: var(--amber-light);
  border-color: var(--amber);
}

.action-icon {
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255,255,255,0.7);
  border-radius: 8px;
  font-size: 16px;
  color: var(--teal);
  flex-shrink: 0;
}

.action-btn.action-secondary .action-icon {
  color: var(--amber);
}

.action-info {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.action-label {
  font-size: 13px;
  font-weight: 500;
  color: var(--text-primary);
}

.action-desc {
  font-size: 11px;
  color: var(--text-secondary);
}

.important-info {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.info-box {
  display: flex;
  gap: 12px;
  padding: 12px;
  border-radius: var(--radius-sm);
  font-size: 12px;
  line-height: 1.5;
}

.info-box i {
  font-size: 18px;
  flex-shrink: 0;
  margin-top: 2px;
}

.info-alert {
  background: #e0f2f1;
  color: var(--teal);
}

.info-alert i {
  color: var(--teal);
}

.info-warning {
  background: #fef3c7;
  color: var(--amber);
}

.info-warning i {
  color: var(--amber);
}

.info-success {
  background: var(--green-light);
  color: var(--green);
}

.info-success i {
  color: var(--green);
}

.info-title {
  font-weight: 600;
  margin-bottom: 2px;
}

.info-text {
  margin: 0;
}

.announcements {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.announcement-item {
  padding: 12px;
  border-left: 3px solid var(--teal);
  background: var(--teal-light);
  border-radius: var(--radius-sm);
}

.ann-date {
  font-size: 11px;
  color: var(--teal);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.ann-text {
  font-size: 12px;
  color: var(--text-primary);
  margin-top: 4px;
  margin-bottom: 0;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 12px;
}
</style>

</body>
</html>
