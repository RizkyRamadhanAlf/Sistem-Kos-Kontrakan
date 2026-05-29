<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pembayaran — KostKu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  @vite(['resources/css/style.css'])
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2>Unggah Bukti Pembayaran</h2>
        <p class="text-muted">Kirim bukti transfer agar tim bisa memverifikasi pembayaran kos/kontrakan.</p>
      </div>
      <a href="/pemilik" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="card mb-5">
      <div class="card-body">
        <form action="{{ route('pembayaran.upload.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="mb-3">
            <label class="form-label">Nama Penyewa</label>
            <input type="text" name="tenant_name" class="form-control" value="{{ old('tenant_name') }}" placeholder="Contoh: Ani Sari" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Jumlah Bayar (Rp)</label>
            <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" placeholder="Contoh: 1500000" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Pembayaran</label>
            <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date') }}" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Bukti Transfer / Struk</label>
            <input type="file" name="receipt" class="form-control" accept="image/*" required />
            <div class="form-text">Unggah file gambar maksimal 2MB.</div>
          </div>

          <button type="submit" class="btn btn-primary">Unggah Bukti Pembayaran</button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Riwayat Unggah Pembayaran</h5>
        <a href="{{ route('pembayaran.verifikasi') }}" class="btn btn-sm btn-outline-primary">Lihat Verifikasi</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
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
                    <span class="badge bg-{{ $payment->status === 'verified' ? 'success' : ($payment->status === 'rejected' ? 'danger' : 'secondary') }}">
                      {{ ucfirst($payment->status) }}
                    </span>
                  </td>
                  <td>
                    <a href="{{ asset($payment->receipt_path) }}" target="_blank" class="link-primary">Lihat</a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center py-4">Belum ada bukti pembayaran yang diunggah.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
