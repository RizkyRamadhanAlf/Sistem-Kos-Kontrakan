<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pembayaran {{ $payment->invoice_number }} - KostKu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --sidebar-bg:#0f1923; --sidebar-hover:#1a2535; --teal:#0d9488; --teal-dark:#0f766e;
      --teal-light:#e0f2f1; --surface:#fff; --border:#e8ecf1; --bg:#f4f6f9;
      --text-primary:#0f1923; --text-secondary:#64748b; --text-muted:#94a3b8;
      --green:#16a34a; --green-light:#dcfce7; --amber:#d97706; --amber-light:#fef3c7;
      --rose:#e11d48; --rose-light:#ffe4e6; --radius:14px;
      --shadow:0 2px 16px rgba(15,25,35,.06); --shadow-md:0 4px 24px rgba(15,25,35,.1);
    }
    * { box-sizing:border-box; }
    body { background:var(--bg); color:var(--text-primary); font-family:'Plus Jakarta Sans',sans-serif; }
    .checkout-nav { background:var(--sidebar-bg); border-bottom:1px solid rgba(255,255,255,.07); box-shadow:0 4px 18px rgba(15,25,35,.16); }
    .brand { color:#fff; font-family:'DM Serif Display',serif; font-size:21px; text-decoration:none; }
    .brand:hover { color:#fff; }
    .brand-mark { align-items:center; background:var(--teal); border-radius:10px; color:#fff; display:inline-flex; height:38px; justify-content:center; width:38px; }
    .secure-pill { background:rgba(13,148,136,.18); border:1px solid rgba(45,212,191,.3); border-radius:999px; color:#5eead4; font-size:12px; font-weight:700; padding:7px 12px; }
    .breadcrumb { font-size:12px; margin:0; }
    .breadcrumb-item a { color:var(--text-secondary); text-decoration:none; }
    .breadcrumb-item.active { color:var(--teal); }
    .checkout-title { font-size:25px; font-weight:800; letter-spacing:-.6px; }
    .text-primary { color:var(--teal) !important; }
    .btn-outline-secondary { --bs-btn-color:var(--text-secondary); --bs-btn-border-color:var(--border); --bs-btn-hover-bg:var(--sidebar-bg); --bs-btn-hover-border-color:var(--sidebar-bg); }
    .btn-outline-danger { --bs-btn-color:var(--rose); --bs-btn-border-color:var(--rose-light); --bs-btn-hover-bg:var(--rose); --bs-btn-hover-border-color:var(--rose); }
    .invoice-bar { background:linear-gradient(135deg,var(--sidebar-bg),#173842); border:1px solid rgba(45,212,191,.18); border-radius:var(--radius); color:#fff; padding:20px 22px; box-shadow:0 12px 30px rgba(15,25,35,.18); }
    .invoice-label { color:#99f6e4; display:block; font-size:11px; font-weight:700; letter-spacing:.7px; text-transform:uppercase; }
    .invoice-value { display:block; font-size:14px; font-weight:700; margin-top:4px; }
    .countdown { font-size:22px; font-weight:800; letter-spacing:1px; }
    .premium-card { background:var(--surface); border:1.5px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden; }
    .premium-card:hover { box-shadow:var(--shadow-md); transform:translateY(-2px); }
    .premium-card, .pay-btn { transition:all .22s ease; }
    .card-pad { padding:22px; }
    .section-title { font-size:16px; font-weight:800; margin:0; }
    .section-sub { color:var(--text-muted); font-size:12px; margin:3px 0 0; }
    .property-image { height:225px; object-fit:cover; width:100%; }
    .property-name { font-size:20px; font-weight:800; }
    .property-address { color:var(--text-secondary); font-size:13px; }
    .info-grid { display:grid; gap:12px; grid-template-columns:repeat(2,minmax(0,1fr)); }
    .info-item { align-items:center; background:var(--bg); border:1px solid var(--border); border-radius:10px; display:flex; gap:11px; padding:12px; }
    .info-icon { align-items:center; background:var(--teal-light); border-radius:10px; color:var(--teal); display:flex; flex:0 0 36px; height:36px; justify-content:center; }
    .info-label { color:var(--text-muted); display:block; font-size:10px; font-weight:700; text-transform:uppercase; }
    .info-value { display:block; font-size:12px; font-weight:700; margin-top:2px; }
    .facility { background:var(--teal-light); border-radius:999px; color:var(--teal-dark); display:inline-flex; font-size:11px; font-weight:700; gap:6px; padding:7px 10px; }
    .summary-row { display:flex; font-size:13px; justify-content:space-between; margin-bottom:13px; }
    .summary-row span:first-child { color:var(--text-secondary); }
    .total-box { background:linear-gradient(135deg,var(--teal-light),var(--green-light)); border:1px solid #99d5cf; border-radius:12px; margin-top:17px; padding:15px; }
    .total-price { color:var(--teal-dark); font-size:22px; font-weight:800; }
    .gateway-card { background:linear-gradient(145deg,#ecfdf5,#f8fafc); border:1px solid #99d5cf; border-radius:12px; padding:16px; }
    .gateway-icon { align-items:center; background:var(--teal); border-radius:12px; color:#fff; display:flex; flex:0 0 42px; height:42px; justify-content:center; }
    .supported-methods { display:flex; flex-wrap:wrap; gap:8px; }
    .supported-method { align-items:center; background:#fff; border:1px solid var(--border); border-radius:8px; color:var(--text-primary); display:inline-flex; font-size:10px; font-weight:800; gap:6px; padding:7px 9px; }
    .supported-method i { color:var(--teal); }
    .trust-grid { display:grid; gap:8px; grid-template-columns:repeat(3,minmax(0,1fr)); }
    .trust-item { align-items:center; color:var(--teal-dark); display:flex; font-size:10px; font-weight:700; gap:5px; }
    .payment-detail { background:var(--bg); border:1px solid var(--border); border-radius:10px; padding:13px; }
    .detail-row { display:flex; font-size:11px; justify-content:space-between; margin-bottom:8px; }
    .detail-row:last-child { margin-bottom:0; }
    .detail-row span:first-child { color:var(--text-secondary); }
    .status-badge { border-radius:999px; font-size:10px; font-weight:800; padding:5px 9px; }
    .status-pending { background:#fef3c7; color:#b45309; } .status-paid { background:#dcfce7; color:#15803d; }
    .status-failed { background:#fee2e2; color:#b91c1c; } .status-expired { background:#e2e8f0; color:#475569; }
    .sticky-summary { position:sticky; top:20px; }
    .pay-btn { background:linear-gradient(135deg,var(--teal),var(--teal-dark)); border:0; border-radius:10px; box-shadow:0 12px 24px rgba(13,148,136,.24); color:#fff; font-size:14px; font-weight:800; padding:14px; width:100%; }
    .pay-btn:hover { box-shadow:0 16px 30px rgba(13,148,136,.3); color:#fff; transform:translateY(-2px); }
    .pay-btn:disabled { box-shadow:none; opacity:.55; transform:none; }
    .safety-box { background:var(--teal-light); border:1px solid #99d5cf; border-radius:12px; color:var(--teal-dark); font-size:11px; padding:13px; }
    .payment-logos { color:var(--text-secondary); display:flex; font-size:20px; gap:14px; margin-top:12px; }
    .fade-up { animation:fadeUp .45s ease both; } @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
    .skeleton { animation:pulse 1.2s infinite; background:var(--border); border-radius:8px; } @keyframes pulse { 50% { opacity:.45; } }
    .page-loader { background:var(--bg); inset:0; position:fixed; z-index:2000; }
    .loader-grid { display:grid; gap:18px; grid-template-columns:1.4fr .8fr; margin:120px auto; max-width:1140px; padding:0 16px; }
    .loader-card { height:260px; } .loader-card.short { height:180px; }
    @media(max-width:991px) { .sticky-summary { position:static; } }
    @media(max-width:575px) { .info-grid,.trust-grid { grid-template-columns:1fr; } .invoice-bar .col-sm-4 { margin-top:12px; } .checkout-title { font-size:21px; } }
  </style>
</head>
<body>
  <div class="page-loader" id="pageLoader"><div class="loader-grid"><div><div class="skeleton loader-card mb-3"></div><div class="skeleton loader-card short"></div></div><div class="skeleton loader-card"></div></div></div>
  @php
    $property = $booking->room?->property;
    $facilities = array_slice($property?->facilities ?? ['WiFi', 'AC', 'CCTV', 'Parkir'], 0, 4);
    $status = $payment->payment_status ?? 'pending';
    $statusLabels = ['pending' => 'Menunggu Pembayaran', 'paid' => 'Berhasil', 'failed' => 'Gagal', 'expired' => 'Kadaluarsa'];
    $deadline = $payment->expired_at ?? now()->addDay();
    $canPay = $status === 'pending' && $booking->status === \App\Models\Booking::STATUS_PENDING;
  @endphp

  <nav class="checkout-nav py-3">
    <div class="container d-flex align-items-center justify-content-between">
      <a href="{{ route('tenant.dashboard') }}" class="brand"><span class="brand-mark me-2"><i class="bi bi-buildings-fill"></i></span>KostKu</a>
      <span class="secure-pill"><i class="bi bi-shield-lock-fill me-1"></i> Pembayaran Aman</span>
    </div>
  </nav>

  <main class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('landing') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ $property ? route('tenant.property-detail', $property) : route('tenant.search') }}">Detail Kos</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tenant.booking-detail', $booking) }}">Booking</a></li>
        <li class="breadcrumb-item active">Pembayaran</li>
      </ol>
    </nav>

    <div class="mb-4">
      <h1 class="checkout-title mb-1">Selesaikan Pembayaran</h1>
      <p class="text-secondary small mb-0">Amankan kamar pilihan Anda sebelum batas waktu pembayaran berakhir.</p>
    </div>

    <section class="invoice-bar mb-4 fade-up">
      <div class="row align-items-center">
        <div class="col-sm-4"><span class="invoice-label">Nomor Invoice</span><span class="invoice-value">{{ $payment->invoice_number }}</span></div>
        <div class="col-sm-4"><span class="invoice-label">Status Pembayaran</span><span class="status-badge status-{{ $status }} d-inline-block mt-1">{{ $statusLabels[$status] ?? ucfirst($status) }}</span></div>
        <div class="col-sm-4 text-sm-end"><span class="invoice-label">Sisa Waktu Pembayaran</span><span class="countdown" id="countdown">--:--:--</span></div>
      </div>
    </section>

    <div class="row g-4 align-items-start">
      <div class="col-lg-7">
        <section class="premium-card fade-up mb-4">
          <img class="property-image" src="{{ $property?->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267' }}" alt="{{ $booking->kos_name }}">
          <div class="card-pad">
            <div class="mb-4">
              <h2 class="property-name mb-1">{{ $booking->kos_name }}</h2>
              <p class="property-address mb-0"><i class="bi bi-geo-alt-fill text-primary me-1"></i>{{ $booking->location }}</p>
            </div>
            <div class="info-grid mb-4">
              <div class="info-item"><span class="info-icon"><i class="bi bi-door-closed-fill"></i></span><span><span class="info-label">Kamar</span><span class="info-value">{{ $booking->room?->room_number ?? $booking->room_type }}</span></span></div>
              <div class="info-item"><span class="info-icon"><i class="bi bi-person-fill"></i></span><span><span class="info-label">Nama Penyewa</span><span class="info-value">{{ $booking->tenant_name }}</span></span></div>
              <div class="info-item"><span class="info-icon"><i class="bi bi-calendar-check-fill"></i></span><span><span class="info-label">Tanggal Masuk</span><span class="info-value">{{ ($booking->check_in_date ?? $booking->booking_date)?->translatedFormat('d F Y') }}</span></span></div>
              <div class="info-item"><span class="info-icon"><i class="bi bi-hourglass-split"></i></span><span><span class="info-label">Durasi Sewa</span><span class="info-value">{{ $booking->duration_months }} Bulan</span></span></div>
            </div>
            <h3 class="section-title mb-3">Fasilitas Utama</h3>
            <div class="d-flex flex-wrap gap-2">
              @foreach($facilities as $facility)<span class="facility"><i class="bi bi-check-circle-fill"></i>{{ $facility }}</span>@endforeach
            </div>
          </div>
        </section>

        <section class="premium-card card-pad fade-up">
          <h2 class="section-title">Detail Pembayaran</h2>
          <p class="section-sub mb-3">Informasi transaksi dan batas pembayaran Anda.</p>
          <div class="payment-detail">
            <div class="detail-row"><span>Invoice</span><strong>{{ $payment->invoice_number }}</strong></div>
            <div class="detail-row"><span>Status</span><strong id="detailStatus">{{ $statusLabels[$status] ?? ucfirst($status) }}</strong></div>
            <div class="detail-row"><span>Batas Pembayaran</span><strong>{{ $deadline->translatedFormat('d F Y, H:i') }} WIB</strong></div>
            <div class="detail-row"><span>Payment Gateway</span><strong>Midtrans Snap</strong></div>
          </div>
        </section>
      </div>

      <div class="col-lg-5">
        <aside class="sticky-summary">
          <section class="premium-card card-pad mb-4 fade-up">
            <h2 class="section-title">Ringkasan Biaya</h2>
            <p class="section-sub mb-4">Tidak ada biaya tersembunyi.</p>
            <div class="summary-row"><span>Harga sewa × {{ $booking->duration_months }} bulan</span><strong>Rp {{ number_format($booking->price_per_month * $booking->duration_months, 0, ',', '.') }}</strong></div>
            <div class="summary-row"><span>Biaya admin</span><strong>Rp {{ number_format($booking->admin_fee, 0, ',', '.') }}</strong></div>
            <div class="summary-row"><span>Diskon</span><strong class="text-success">- Rp 0</strong></div>
            <div class="total-box d-flex align-items-center justify-content-between"><span><small class="text-secondary d-block">Total Pembayaran</small><strong>Bayar Sekarang</strong></span><span class="total-price">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span></div>
          </section>

          <section class="premium-card card-pad fade-up">
            <div class="gateway-card mb-4">
              <div class="d-flex align-items-center gap-3 mb-3">
                <span class="gateway-icon"><i class="bi bi-shield-lock-fill"></i></span>
                <div>
                  <h2 class="section-title">Pembayaran Aman &amp; Terpercaya</h2>
                  <p class="section-sub">Diproses melalui Midtrans Payment Gateway secara aman dan terenkripsi.</p>
                </div>
              </div>
              <p class="section-sub mb-2">Metode pembayaran yang didukung:</p>
              <div class="supported-methods mb-3">
                @foreach([
                  ['QRIS','bi-qr-code'], ['BCA VA','bi-bank'], ['BNI VA','bi-bank'], ['BRI VA','bi-bank'],
                  ['Mandiri VA','bi-bank'], ['GoPay','bi-wallet2'], ['DANA','bi-wallet2'], ['ShopeePay','bi-wallet2'],
                  ['Kartu Kredit / Debit','bi-credit-card'], ['Indomaret','bi-shop'], ['Alfamart','bi-shop']
                ] as [$name,$icon])
                  <span class="supported-method"><i class="bi {{ $icon }}"></i>{{ $name }}</span>
                @endforeach
              </div>
              <div class="trust-grid">
                <span class="trust-item"><i class="bi bi-check-circle-fill"></i>Transaksi Aman</span>
                <span class="trust-item"><i class="bi bi-check-circle-fill"></i>Data Terenkripsi</span>
                <span class="trust-item"><i class="bi bi-check-circle-fill"></i>Pembayaran Instan</span>
              </div>
            </div>

            <button id="payNow" class="pay-btn mb-3" {{ $canPay ? '' : 'disabled' }}><i class="bi bi-shield-check me-2"></i>Bayar Sekarang</button>
            <a href="{{ $property ? route('tenant.property-detail', $property) : route('tenant.booking-detail', $booking) }}" class="btn btn-outline-secondary w-100 mb-2"><i class="bi bi-arrow-left me-2"></i>Kembali ke Detail Kos</a>
            @if($booking->status === \App\Models\Booking::STATUS_PENDING)
              <form action="{{ route('booking.cancel', $booking) }}" method="POST" onsubmit="return confirm('Batalkan booking ini?')">@csrf<button class="btn btn-outline-danger w-100"><i class="bi bi-x-circle me-2"></i>Batalkan Booking</button></form>
            @endif

            <div class="safety-box mt-4"><strong><i class="bi bi-lock-fill me-1"></i>Pembayaran Aman melalui Midtrans</strong><div class="mt-1">Pilih metode pembayaran langsung pada popup resmi Midtrans setelah menekan Bayar Sekarang.</div><div class="payment-logos"><i class="bi bi-shield-check"></i><i class="bi bi-credit-card-2-front"></i><i class="bi bi-qr-code"></i><strong style="font-size:12px">MIDTRANS</strong></div></div>
          </section>
        </aside>
      </div>
    </div>
  </main>

  @if(!empty($clientKey))
    <script src="{{ $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $clientKey }}"></script>
  @endif
  <script>
    const payBtn = document.getElementById('payNow');
    const countdown = document.getElementById('countdown');
    const detailStatus = document.getElementById('detailStatus');
    const deadline = new Date(@json($deadline->toIso8601String())).getTime();
    let expirySynced = false;
    window.addEventListener('load', () => document.getElementById('pageLoader')?.remove());

    function updateCountdown() {
      const distance = deadline - Date.now();
      if (distance <= 0) {
        countdown.textContent = '00:00:00';
        detailStatus.textContent = 'Kadaluarsa';
        payBtn.disabled = true;
        if (!expirySynced) {
          expirySynced = true;
          fetch(@json(route('booking.expire', $booking)), {
            method:'POST',
            headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}
          });
        }
        return;
      }
      const hours = Math.floor(distance / 3600000);
      const minutes = Math.floor((distance % 3600000) / 60000);
      const seconds = Math.floor((distance % 60000) / 1000);
      countdown.textContent = [hours, minutes, seconds].map(value => String(value).padStart(2, '0')).join(':');
    }
    updateCountdown();
    setInterval(updateCountdown, 1000);

    payBtn?.addEventListener('click', async () => {
      if (!window.snap) {
        alert('Midtrans belum siap. Periksa MIDTRANS_CLIENT_KEY.');
        return;
      }
      payBtn.disabled = true;
      payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyiapkan Pembayaran...';
      try {
        const response = await fetch(@json(route('booking.payment.snap', $booking)), {
          method: 'POST',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}
        });
        const result = await response.json();
        if (!response.ok) throw new Error(result.error || 'Gagal membuat transaksi.');
        window.snap.pay(result.token, {
            onSuccess: () => window.location = @json(route('payment.check-status', $payment)),

            onPending: function(result) {
                detailStatus.textContent = 'Menunggu Pembayaran';
                resetButton();
            },

            onError: () => window.location = @json(route('payments.fail')),

            onClose: function() {
                resetButton();
            }
        });
      } catch (error) {
        alert(error.message);
        resetButton();
      }
    });
    function resetButton() {
      payBtn.disabled = false;
      payBtn.innerHTML = '<i class="bi bi-shield-check me-2"></i>Bayar Sekarang';
    }
  </script>
</body>
</html>
