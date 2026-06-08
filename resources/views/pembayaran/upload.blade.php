<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KostKu — Unggah Pembayaran</title>

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
        <span class="user-name">Penyewa</span>
        <span class="user-role">Pengguna</span>
      </div>
      <i class="bi bi-box-arrow-right logout-icon"></i>
    </div>
  </aside>

  <main class="main-content flex-grow-1">
    <header class="topbar d-flex align-items-center justify-content-between">
      <div>
        <h4 class="topbar-title">Unggah Bukti Pembayaran</h4>
        <p class="topbar-sub">Kirim bukti transfer agar pembayaran kos/kontrakan segera diverifikasi.</p>
      </div>
      <div class="topbar-actions d-flex align-items-center gap-3">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Cari pembayaran…" />
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
                <h6 class="panel-title">Form Unggah Pembayaran</h6>
                <p class="panel-sub">Unggah bukti pembayaran Anda dengan benar dan cepat.</p>
              </div>
              <a href="{{ route('pembayaran.verifikasi') }}" class="see-all-link">Lihat Verifikasi</a>
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

            <form action="{{ route('pembayaran.upload.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label class="form-label" for="tenant_name">Nama Penyewa</label>
                <input type="text" class="form-control" id="tenant_name" name="tenant_name" value="{{ old('tenant_name') }}" placeholder="Contoh: Ani Sari" required />
              </div>

              <div class="mb-3">
                <label class="form-label" for="amount">Jumlah Bayar (Rp)</label>
                <input type="text" class="form-control" id="amount" name="amount" value="{{ old('amount') ? number_format(preg_replace('/\\D/', '', old('amount')), 0, ',', '.') : '' }}" placeholder="Contoh: 1.500.000" required />
              </div>

              <div class="mb-3">
                <label class="form-label" for="payment_date">Tanggal Pembayaran</label>
                <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ old('payment_date') }}" required />
              </div>

              <div class="mb-3">
                <label class="form-label" for="receipt">Bukti Transfer / Struk</label>
                <input type="file" class="form-control" id="receipt" name="receipt" accept="image/*" required />
                <div class="form-text">Unggah file gambar maksimal 2MB.</div>
              </div>

              <button type="submit" class="btn-add">Unggah Bukti Pembayaran</button>
            </form>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel">
            <div class="panel-header">
              <div>
                <h6 class="panel-title">Ringkasan Pembayaran</h6>
                <p class="panel-sub">Lihat statistik pengunggahan dalam tampilan cepat.</p>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-sm-6">
                <div class="stat-card card-teal">
                  <div class="stat-icon"><i class="bi bi-upload"></i></div>
                  <div class="stat-info">
                    <span class="stat-label">Total Unggah</span>
                    <span class="stat-value">{{ $payments->count() }}</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="stat-card card-green">
                  <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                  <div class="stat-info">
                    <span class="stat-label">Terverifikasi</span>
                    <span class="stat-value">{{ $payments->where('status', App\Models\Payment::STATUS_VERIFIED)->count() }}</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="stat-card card-amber">
                  <div class="stat-icon"><i class="bi bi-clock-fill"></i></div>
                  <div class="stat-info">
                    <span class="stat-label">Menunggu</span>
                    <span class="stat-value">{{ $payments->where('status', App\Models\Payment::STATUS_PENDING)->count() }}</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="stat-card card-rose">
                  <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
                  <div class="stat-info">
                    <span class="stat-label">Ditolak</span>
                    <span class="stat-value">{{ $payments->where('status', App\Models\Payment::STATUS_REJECTED)->count() }}</span>
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
            <h6 class="panel-title">Riwayat Unggah Pembayaran</h6>
            <p class="panel-sub">Bukti pembayaran terbaru yang telah Anda kirim.</p>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Nama Penyewa</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Bukti</th>
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
                  <td><a href="{{ asset($payment->receipt_path) }}" target="_blank" class="link-primary">Lihat</a></td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-4">Belum ada bukti pembayaran yang diunggah.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
    </div>

    <script>
      const amountInput = document.getElementById('amount');

      const formatRupiah = (value) => {
        const cleaned = value.replace(/\D/g, '');
        return cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      };

      const normalizeRupiah = (value) => value.replace(/\./g, '');

      if (amountInput) {
        const form = amountInput.closest('form');

        amountInput.addEventListener('input', (event) => {
          const originalValue = event.target.value;
          const caretPosition = event.target.selectionStart;
          const formattedValue = formatRupiah(originalValue);
          event.target.value = formattedValue;

          const diff = formattedValue.length - originalValue.length;
          const newPosition = Math.max(0, caretPosition + diff);
          event.target.setSelectionRange(newPosition, newPosition);
        });

        if (form) {
          form.addEventListener('submit', () => {
            amountInput.value = normalizeRupiah(amountInput.value);
          });
        }
      }
    </script>
  </body>
</html>
