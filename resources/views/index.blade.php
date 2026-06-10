<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KostKu — Platform Pemesanan Kos dan Kontrakan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <style>
        :root {
            --primary: #0d9488;
            --primary-soft: #d1fae5;
            --secondary: #2563eb;
            --surface: #ffffff;
            --surface-soft: #f8fbff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --radius-lg: 28px;
            --radius-md: 20px;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background: #eef5fb;
            color: var(--text-dark);
        }

        a { text-decoration: none; }
        img { max-width: 100%; display: block; }

        .landing {
            max-width: 1300px;
            margin: 0 auto;
            padding: 32px 24px 48px;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-dark);
            font-weight: 800;
            font-size: 1.15rem;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: var(--primary);
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.2rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: var(--text-dark);
            font-weight: 500;
            transition: color .2s ease;
        }

        .nav-links a:hover { color: var(--primary); }

        .btn-group-top {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .flash-message {
            position: fixed;
            top: 82px;
            left: 50%;
            z-index: 1000;
            transform: translateX(-50%);
            padding: 12px 18px;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            background: #f0fdf4;
            color: #15803d;
            box-shadow: 0 8px 28px rgba(15, 25, 35, .12);
            font-size: 13px;
            font-weight: 600;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 42px;
            align-items: center;
            margin-top: 40px;
            padding: 40px 0 60px;
        }

        .hero-copy {
            max-width: 620px;
        }

        .hero-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(13,148,136,0.12);
            color: var(--primary);
            border-radius: 999px;
            padding: 10px 16px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-size: 0.78rem;
        }

        .hero-title {
            margin-top: 24px;
            font-size: clamp(2.8rem, 5vw, 4.6rem);
            line-height: 1.02;
            font-weight: 900;
        }

        .hero-text {
            margin: 24px 0 32px;
            color: var(--text-muted);
            font-size: 1.05rem;
            line-height: 1.85;
            max-width: 560px;
        }

        .search-card {
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: 0 28px 80px rgba(15,23,42,0.08);
            border: 1px solid rgba(15,23,42,0.06);
            padding: 28px;
            display: grid;
            gap: 18px;
        }

        .search-row {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 18px;
        }

        .search-row select,
        .search-row input {
            width: 100%;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 16px 18px;
            font-size: 0.95rem;
        }

        .search-row label {
            font-weight: 700;
            color: var(--text-dark);
            display: block;
            margin-bottom: 8px;
            font-size: 0.85rem;
        }

        .search-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .search-actions .btn {
            min-width: 180px;
            border-radius: 999px;
            font-weight: 700;
            padding: 14px 28px;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-top: 18px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 22px;
            padding: 22px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .stat-card strong {
            display: block;
            font-size: 1.8rem;
            margin-bottom: 8px;
            color: var(--primary);
        }

        .stat-card span {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .hero-image {
            position: relative;
            overflow: hidden;
            border-radius: 32px;
            min-height: 560px;
        }

        .hero-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(13,148,136,0.2), rgba(255,255,255,0.05));
        }

        .section {
            background: #ffffff;
            border-radius: var(--radius-lg);
            padding: 48px;
            margin-bottom: 36px;
            box-shadow: 0 20px 50px rgba(15,23,42,0.06);
        }

        .section-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 28px;
        }

        .features-grid,
        .popular-grid,
        .category-grid,
        .steps-grid,
        .facility-grid,
        .testimonial-grid {
            display: grid;
            gap: 24px;
        }

        .features-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .popular-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .category-grid { grid-template-columns: repeat(6, minmax(0, 1fr)); }
        .steps-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .facility-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .testimonial-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }

        .feature-card,
        .category-card,
        .step-card,
        .facility-card,
        .testimonial-card,
        .popular-card {
            background: #f8fbff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            padding: 26px;
            transition: transform .24s ease, box-shadow .24s ease;
        }

        .feature-card:hover,
        .category-card:hover,
        .step-card:hover,
        .facility-card:hover,
        .testimonial-card:hover,
        .popular-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 42px rgba(15,23,42,0.08);
        }

        .feature-card i,
        .category-card i,
        .step-card i,
        .facility-card i {
            width: 56px;
            height: 56px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            background: rgba(13,148,136,0.14);
            color: var(--primary);
            font-size: 1.4rem;
        }

        .feature-card h5,
        .category-card h6,
        .step-card h6 {
            margin: 0;
            font-weight: 800;
        }

        .feature-card p,
        .step-card p,
        .testimonial-text,
        .facility-card span {
            margin: 0;
            color: var(--text-muted);
            line-height: 1.75;
            font-size: 0.95rem;
        }

        .popular-card img {
            width: 100%;
            border-radius: 22px;
            margin-bottom: 18px;
            min-height: 210px;
            object-fit: cover;
        }

        .popular-card .popular-meta {
            display: flex;
            justify-content: space-between;
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .popular-card .popular-title {
            font-size: 1.05rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .popular-card .popular-price {
            color: var(--primary);
            font-weight: 800;
            margin-bottom: 14px;
        }

        .popular-card .popular-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 18px;
        }

        .popular-card .tag {
            background: rgba(37,99,235,0.08);
            color: var(--secondary);
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 0.82rem;
        }

        .popular-card .btn {
            min-width: 134px;
            border-radius: 999px;
            font-weight: 700;
        }

        .category-card {
            text-align: center;
            padding-top: 32px;
            padding-bottom: 32px;
        }

        .category-card h6 {
            margin-top: 16px;
            font-size: 0.98rem;
            font-weight: 800;
        }

        .step-card {
            text-align: center;
        }

        .step-card .step-number {
            color: var(--secondary);
            font-weight: 700;
            margin-bottom: 14px;
        }

        .facility-card {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .facility-card span {
            font-weight: 700;
            color: var(--text-dark);
        }

        .testimonial-card {
            display: flex;
            flex-direction: column;
            min-height: 280px;
        }

        .testimonial-meta {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }

        .testimonial-meta img {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            object-fit: cover;
        }

        .testimonial-meta h6 {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
        }

        .testimonial-meta p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .testimonial-stars {
            color: #f59e0b;
            margin-bottom: 12px;
        }

        .testimonial-text {
            flex-grow: 1;
        }

        .cta-banner {
            background: linear-gradient(135deg, #e5faf5 0%, #ffffff 100%);
            border-radius: 30px;
            padding: 42px 42px;
            display: grid;
            grid-template-columns: 1.4fr 0.6fr;
            align-items: center;
            gap: 24px;
        }

        .cta-banner h2 {
            margin: 0 0 16px;
            font-size: 2.2rem;
            font-weight: 900;
        }

        .cta-banner p {
            margin: 0;
            color: var(--text-muted);
            line-height: 1.8;
            max-width: 560px;
        }

        .footer {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr;
            gap: 32px;
            padding-top: 36px;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .footer-title {
            margin-bottom: 16px;
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        .footer a {
            color: var(--text-muted);
            margin-bottom: 10px;
            display: block;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .footer-note {
            margin-top: 26px;
            color: #94a3b8;
            grid-column: 1 / -1;
        }

        @media (max-width: 1080px) {
            .hero { grid-template-columns: 1fr; }
            .hero-stats { grid-template-columns: 1fr; }
            .features-grid,
            .popular-grid,
            .category-grid,
            .steps-grid,
            .facility-grid,
            .testimonial-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .footer { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .cta-banner { grid-template-columns: 1fr; }
        }

        @media (max-width: 760px) {
            .landing { padding: 24px 16px 32px; }
            .navbar { flex-direction: column; align-items: flex-start; }
            .nav-links { gap: 14px; }
            .search-row { grid-template-columns: 1fr; }
            .features-grid,
            .popular-grid,
            .category-grid,
            .steps-grid,
            .facility-grid,
            .testimonial-grid { grid-template-columns: 1fr; }
            .hero-image { min-height: 380px; }
        }
    </style>
</head>
<body>
    <div class="landing">
        @if(session('status'))
            <div class="flash-message">{{ session('status') }}</div>
        @endif

        <header class="navbar">
            <a href="/" class="brand">
                <div class="brand-icon"><i class="bi bi-buildings-fill"></i></div>
                KostKu
            </a>
            <nav class="nav-links">
                <a href="#beranda">Beranda</a>
                <a href="#cari-kos">Cari Kos</a>
                <a href="#kontrakan">Kontrakan</a>
                <a href="#tentang-kami">Tentang Kami</a>
                <a href="#bantuan">Bantuan</a>
            </nav>
            <div class="btn-group-top">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm">Logout</button>
                    </form>
                @endguest
            </div>
        </header>

        <section class="hero" id="beranda">
            <div class="hero-copy">
                <span class="hero-label"><i class="bi bi-building"></i> Cari Kos & Kontrakan</span>
                <h1 class="hero-title">Temukan Kos Impianmu dengan Mudah</h1>
                <p class="hero-text">Cari, bandingkan, dan pesan kos atau kontrakan secara online hanya dalam beberapa menit.</p>

                <div class="search-card">
                    <div class="search-row">
                        <div>
                            <label for="location">Lokasi</label>
                            <input id="location" type="text" placeholder="Contoh: Jakarta, Bandung, Surabaya" />
                        </div>
                        <div>
                            <label for="price">Filter Harga</label>
                            <select id="price">
                                <option>Semua Harga</option>
                                <option>Rp 500.000 - Rp 1.000.000</option>
                                <option>Rp 1.000.000 - Rp 1.500.000</option>
                                <option>Rp 1.500.000+</option>
                            </select>
                        </div>
                    </div>
                    <div class="search-row">
                        <div>
                            <label for="type">Tipe Kos</label>
                            <select id="type">
                                <option>Semua Tipe</option>
                                <option>Putra</option>
                                <option>Putri</option>
                                <option>Campur</option>
                                <option>Kontrakan</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-end">
                            <button class="btn btn-success btn-search">Cari Sekarang</button>
                        </div>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-card"><strong>1000+</strong><span>Kamar Tersedia</span></div>
                        <div class="stat-card"><strong>500+</strong><span>Pemilik Kos</span></div>
                        <div class="stat-card"><strong>5000+</strong><span>Pengguna Aktif</span></div>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80" alt="Kos modern" />
            </div>
        </section>

        <section class="section">
            <div class="section-title">Keunggulan KostKu</div>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="bi bi-search"></i>
                    <h5>Mudah Dicari</h5>
                    <p>Temukan kos dan kontrakan dengan filter lokasi, harga, dan tipe secara cepat.</p>
                </div>
                <div class="feature-card">
                    <i class="bi bi-calendar-check"></i>
                    <h5>Booking Online</h5>
                    <p>Pesan kamar langsung dari satu platform tanpa proses berbelit.</p>
                </div>
                <div class="feature-card">
                    <i class="bi bi-chat-square-text"></i>
                    <h5>Review Pengguna</h5>
                    <p>Nilai dan rekomendasi nyata membantu Anda memilih yang paling sesuai.</p>
                </div>
                <div class="feature-card">
                    <i class="bi bi-shield-lock"></i>
                    <h5>Pembayaran Aman</h5>
                    <p>Transaksi terlindungi dan bukti pembayaran tersimpan dengan rapi.</p>
                </div>
            </div>
        </section>

        <section class="section" id="cari-kos">
            <div class="section-title">Kos Populer</div>
            <div class="popular-grid">
                <div class="popular-card">
                    <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80" alt="Kost Putri Melati" />
                    <div class="popular-meta"><span>Jakarta Barat</span><span>4.8 ★</span></div>
                    <div class="popular-title">Kost Putri Melati</div>
                    <div class="popular-price">Rp 1.200.000 / bulan</div>
                    <div class="popular-tags"><span class="tag">WiFi</span><span class="tag">AC</span><span class="tag">Kamar Mandi Dalam</span></div>
                    <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                </div>
                <div class="popular-card">
                    <img src="https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80" alt="Kost Campur Harmony" />
                    <div class="popular-meta"><span>Bandung</span><span>4.7 ★</span></div>
                    <div class="popular-title">Kost Campur Harmony</div>
                    <div class="popular-price">Rp 950.000 / bulan</div>
                    <div class="popular-tags"><span class="tag">Parkir</span><span class="tag">Dapur Bersama</span><span class="tag">CCTV</span></div>
                    <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                </div>
                <div class="popular-card">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80" alt="Kost Putra Amanah" />
                    <div class="popular-meta"><span>Yogyakarta</span><span>4.9 ★</span></div>
                    <div class="popular-title">Kost Putra Amanah</div>
                    <div class="popular-price">Rp 850.000 / bulan</div>
                    <div class="popular-tags"><span class="tag">WiFi</span><span class="tag">Parkir</span><span class="tag">Keamanan</span></div>
                    <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                </div>
                <div class="popular-card">
                    <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80" alt="Kost Premium Sutera" />
                    <div class="popular-meta"><span>Surabaya</span><span>4.6 ★</span></div>
                    <div class="popular-title">Kost Premium Sutera</div>
                    <div class="popular-price">Rp 1.800.000 / bulan</div>
                    <div class="popular-tags"><span class="tag">AC</span><span class="tag">Laundry</span><span class="tag">Kamar Mandi Dalam</span></div>
                    <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                </div>
                <div class="popular-card">
                    <img src="https://images.unsplash.com/photo-1592928303384-25f2296ecdf0?auto=format&fit=crop&w=800&q=80" alt="Kontrakan Griya Asri" />
                    <div class="popular-meta"><span>Malang</span><span>4.5 ★</span></div>
                    <div class="popular-title">Kontrakan Griya Asri</div>
                    <div class="popular-price">Rp 2.300.000 / bulan</div>
                    <div class="popular-tags"><span class="tag">Parkir</span><span class="tag">Dapur</span><span class="tag">Keamanan</span></div>
                    <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                </div>
                <div class="popular-card">
                    <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80" alt="Kost Dekat Kampus Muda" />
                    <div class="popular-meta"><span>Bandung</span><span>4.8 ★</span></div>
                    <div class="popular-title">Kost Dekat Kampus Muda</div>
                    <div class="popular-price">Rp 1.050.000 / bulan</div>
                    <div class="popular-tags"><span class="tag">WiFi</span><span class="tag">Laundry</span><span class="tag">Dekat Kampus</span></div>
                    <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                </div>
            </div>
        </section>

        <section class="section" id="kontrakan">
            <div class="section-title">Kategori Kos</div>
            <div class="category-grid">
                <div class="category-card"><i class="bi bi-person"></i><h6>Kos Putra</h6></div>
                <div class="category-card"><i class="bi bi-person-fill"></i><h6>Kos Putri</h6></div>
                <div class="category-card"><i class="bi bi-people"></i><h6>Kos Campur</h6></div>
                <div class="category-card"><i class="bi bi-house-heart"></i><h6>Kontrakan</h6></div>
                <div class="category-card"><i class="bi bi-star"></i><h6>Kos Premium</h6></div>
                <div class="category-card"><i class="bi bi-building"></i><h6>Kos Dekat Kampus</h6></div>
            </div>
        </section>

        <section class="section" id="tentang-kami">
            <div class="section-title">Cara Pemesanan</div>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <i class="bi bi-search"></i>
                    <h6>Cari Kos</h6>
                    <p>Pilih lokasi dan tipe kos yang paling sesuai dengan kebutuhanmu.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <i class="bi bi-card-checklist"></i>
                    <h6>Lihat Detail</h6>
                    <p>Periksa fasilitas, rating, dan harga sebelum memesan.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <i class="bi bi-door-open"></i>
                    <h6>Booking Kamar</h6>
                    <p>Pesan kamar secara online dengan tombol booking yang mudah.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">4</div>
                    <i class="bi bi-wallet2"></i>
                    <h6>Lakukan Pembayaran</h6>
                    <p>Bayar melalui sistem dan terima konfirmasi secara instan.</p>
                </div>
            </div>
        </section>

        <section class="section" id="bantuan">
            <div class="section-title">Fasilitas Unggulan</div>
            <div class="facility-grid">
                <div class="facility-card"><i class="bi bi-wifi"></i><span>WiFi</span></div>
                <div class="facility-card"><i class="bi bi-snow"></i><span>AC</span></div>
                <div class="facility-card"><i class="bi bi-shower"></i><span>Kamar Mandi Dalam</span></div>
                <div class="facility-card"><i class="bi bi-camera-video"></i><span>CCTV</span></div>
                <div class="facility-card"><i class="bi bi-car-front"></i><span>Parkir</span></div>
                <div class="facility-card"><i class="bi bi-droplet"></i><span>Laundry</span></div>
                <div class="facility-card"><i class="bi bi-cup-straw"></i><span>Dapur Bersama</span></div>
                <div class="facility-card"><i class="bi bi-shield-check"></i><span>Keamanan 24 Jam</span></div>
            </div>
        </section>

        <section class="section">
            <div class="section-title">Testimoni Pengguna</div>
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <div class="testimonial-meta">
                        <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80" alt="Sari Anggraeni" />
                        <div>
                            <h6>Sari Anggraeni</h6>
                            <p>Mahasiswa</p>
                        </div>
                    </div>
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"KostKu membuat proses mencari kos jadi sangat mudah. Saya menemukan kamar yang cocok dalam waktu 10 menit saja."</p>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-meta">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=80" alt="Rizki Pratama" />
                        <div>
                            <h6>Rizki Pratama</h6>
                            <p>Penyewa</p>
                        </div>
                    </div>
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"Booking online mudah, pembayaran aman, dan semua informasi kos lengkap. Highly recommended!"</p>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-meta">
                        <img src="https://images.unsplash.com/photo-1552058544-f2b08422138a?auto=format&fit=crop&w=500&q=80" alt="Aulia Putri" />
                        <div>
                            <h6>Aulia Putri</h6>
                            <p>Karyawan</p>
                        </div>
                    </div>
                    <div class="testimonial-stars">★★★★☆</div>
                    <p class="testimonial-text">"Fitur review sangat membantu untuk memilih kos yang nyaman dan aman. Tampilan websitenya juga modern."</p>
                </div>
            </div>
        </section>

        <section class="cta-banner">
            <div>
                <h2>Siap Menemukan Kos Impianmu?</h2>
                <p>Temukan pilihan kos dan kontrakan terbaik di KostKu dengan pengalaman yang cepat, aman, dan terpercaya.</p>
            </div>
            <div>
                <a href="#cari-kos" class="btn btn-success btn-lg">Cari Kos Sekarang</a>
            </div>
        </section>

        <footer class="footer">
            <div>
                <div class="footer-logo">
                    <div class="brand-icon"><i class="bi bi-buildings-fill"></i></div>
                    <div>
                        <div class="footer-title">KostKu</div>
                        <p>Platform pemesanan kos dan kontrakan terdepan untuk menemukan hunian nyaman.</p>
                    </div>
                </div>
            </div>
            <div>
                <div class="footer-title">Link Cepat</div>
                <a href="#beranda">Beranda</a>
                <a href="#cari-kos">Cari Kos</a>
                <a href="#kontrakan">Kontrakan</a>
                <a href="#tentang-kami">Tentang Kami</a>
            </div>
            <div>
                <div class="footer-title">Kontak</div>
                <a href="mailto:halo@kostku.id">halo@kostku.id</a>
                <a href="tel:+628123456789">+62 812-3456-789</a>
                <a href="#bantuan">Bantuan</a>
            </div>
            <div>
                <div class="footer-title">Media Sosial</div>
                <a href="#">Instagram</a>
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
            </div>
            <div class="footer-note">© 2025 KostKu. Semua hak dilindungi.</div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
