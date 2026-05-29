<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verifikasi Pembayaran — KostKu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  @vite(['resources/css/style.css'])
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2>Daftar Verifikasi Pembayaran</h2>
        <p class="text-muted">Verifikasi bukti pembayaran yang telah dikirim penyewa.</p>
      </div>
      <a href="{{ route('pembayaran.upload') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Nama Penyewa</th>
                <th>Jumlah</th>
                <th>Bayar</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Aksi</th>
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
                  <td>{{ $payment->notes ?? '-' }}</td>
                  <td class="text-nowrap">
                    <a href="{{ asset($payment->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-info mb-1">Bukti</a>
                    <form action="{{ route('pembayaran.verify', $payment) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="action" value="verified" />
                      <button type="submit" class="btn btn-sm btn-success mb-1">Verifikasi</button>
                    </form>
                    <form action="{{ route('pembayaran.verify', $payment) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="action" value="rejected" />
                      <button type="submit" class="btn btn-sm btn-danger mb-1">Tolak</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center py-4">Belum ada pembayaran yang perlu diverifikasi.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
