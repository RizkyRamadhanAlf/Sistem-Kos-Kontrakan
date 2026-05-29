<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>KostKu - Guest Dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet"/>

  @vite(['resources/css/style.css'])
</head>
<body>
<div class="wrapper d-flex">
  <aside class="sidebar d-flex flex-column">
    <div class="sidebar-brand">
      <div class="brand-icon"><i class="bi bi-buildings-fill"></i></div>
      <div>
        <span class="brand-name">KostKu</span>
        <span class="brand-sub">Guest Area</span>
      </div>
    </div>

    <nav class="sidebar-nav flex-grow-1">
      <p class="nav-label">Menu Tamu</p>
      <a href="#" class="nav-item active">
        <i class="bi bi-stars"></i> Rekomendasi Kost
      </a>
      <a href="#" class="nav-item">
        <i class="bi bi-search"></i> Cari Kost
      </a>
      <a href="#" class="nav-item">
        <i class="bi bi-info-circle-fill"></i> Tentang KostKu
      </a>
    </nav>

    <div class="sidebar-user">
      <img src="https://i.pravatar.cc/40?img=15" alt="guest" class="user-avatar"/>
      <div class="user-info">
        <span class="user-name">Pengunjung</span>
        <span class="user-role">Belum login</span>
      </div>
    </div>
  </aside>

  <main class="main-content flex-grow-1">
    <header class="topbar d-flex align-items-center justify-content-between">
      <div>
        <h4 class="topbar-title">Temukan Kost Idaman Anda</h4>
        <p class="topbar-sub">Laman awal tamu dengan rekomendasi kost terbaik</p>
      </div>

      <div class="topbar-actions d-flex align-items-center gap-2">
        @if (Route::has('login'))
          <a href="{{ route('login') }}" class="btn-ghost" style="width:auto;height:auto;padding:8px 14px;">Login</a>
        @else
          <a href="#" class="btn-ghost" style="width:auto;height:auto;padding:8px 14px;">Login</a>
        @endif

        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="btn-add">Register</a>
        @else
          <a href="#" class="btn-add">Register</a>
        @endif
      </div>
    </header>

    <div class="content-body">
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Bar Pencarian Kost</h6>
                <p class="panel-sub">Cari berdasarkan lokasi, harga, atau fasilitas</p>
              </div>
            </div>

            <form class="search-box" style="max-width:none;">
              <i class="bi bi-search"></i>
              <input type="text" name="q" placeholder="Contoh: Kost dekat UI, Depok, harga di bawah 1.5jt" />
            </form>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-12">
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Rekomendasi Kost Untuk Anda</h6>
                <p class="panel-sub">Pilihan populer dari berbagai area</p>
              </div>
              <a href="#" class="see-all-link">Lihat semua</a>
            </div>

            <div class="property-list">
              <div class="property-item">
                <div class="prop-thumb" style="background:#e0f2f1;">
                  <i class="bi bi-house-heart-fill" style="color:#0d9488;"></i>
                </div>
                <div class="prop-info">
                  <span class="prop-name">Kost Putri Melati Residence</span>
                  <span class="prop-addr"><i class="bi bi-geo-alt-fill"></i> Kebon Jeruk, Jakarta Barat</span>
                </div>
                <div class="prop-stat">
                  <div class="occ-bar">
                    <div class="occ-fill" style="width:88%;background:#0d9488;"></div>
                  </div>
                  <span class="occ-text">Rating 4.8/5</span>
                </div>
                <span class="prop-badge badge-kost">Rp 1.200.000</span>
              </div>

              <div class="property-item">
                <div class="prop-thumb" style="background:#fef3c7;">
                  <i class="bi bi-building-fill" style="color:#d97706;"></i>
                </div>
                <div class="prop-info">
                  <span class="prop-name">Kost Harmoni Point</span>
                  <span class="prop-addr"><i class="bi bi-geo-alt-fill"></i> Margonda, Depok</span>
                </div>
                <div class="prop-stat">
                  <div class="occ-bar">
                    <div class="occ-fill" style="width:81%;background:#d97706;"></div>
                  </div>
                  <span class="occ-text">Rating 4.6/5</span>
                </div>
                <span class="prop-badge badge-kontrakan">Rp 950.000</span>
              </div>

              <div class="property-item">
                <div class="prop-thumb" style="background:#dcfce7;">
                  <i class="bi bi-house-door-fill" style="color:#16a34a;"></i>
                </div>
                <div class="prop-info">
                  <span class="prop-name">Kost Bahagia Living</span>
                  <span class="prop-addr"><i class="bi bi-geo-alt-fill"></i> Cempaka Putih, Jakarta Pusat</span>
                </div>
                <div class="prop-stat">
                  <div class="occ-bar">
                    <div class="occ-fill" style="width:84%;background:#16a34a;"></div>
                  </div>
                  <span class="occ-text">Rating 4.7/5</span>
                </div>
                <span class="prop-badge badge-kost">Rp 1.050.000</span>
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
