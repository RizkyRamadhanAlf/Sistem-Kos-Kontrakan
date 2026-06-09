<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran Booking - {{ $booking->kos_name ?? 'Booking' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      .payment-method { cursor: pointer; }
      .method-card { transition: transform .12s ease; }
      .method-card:hover { transform: translateY(-4px); }
      @media (max-width: 768px) { .flex-md-row { flex-direction: column; } }
    </style>
  </head>
  <body class="bg-light">
    <div class="container py-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Pembayaran Booking</h3>
        <div>
          <a href="/" class="btn btn-outline-secondary btn-sm">Kembali ke Detail Kos</a>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Ringkasan Booking</h5>
              <p class="mb-1"><strong>Nama kos:</strong> {{ $booking->kos_name }}</p>
              <p class="mb-1"><strong>Tipe kamar:</strong> {{ $booking->room_type }}</p>
              <p class="mb-1"><strong>Lokasi:</strong> {{ $booking->location }}</p>
              <p class="mb-1"><strong>Nama pemesan:</strong> {{ $booking->tenant_name }}</p>
              <p class="mb-1"><strong>Tanggal booking:</strong> {{ optional($booking->booking_date)->format('d M Y') }}</p>
              <p class="mb-1"><strong>Durasi sewa:</strong> {{ $booking->duration_months }} bulan</p>
              <p class="mb-1"><strong>Harga sewa / bulan:</strong> Rp {{ number_format($booking->price_per_month,0,',','.') }}</p>
              <p class="mb-1"><strong>Biaya admin:</strong> Rp {{ number_format($booking->admin_fee,0,',','.') }}</p>
              <hr>
              <h5>Total Pembayaran</h5>
              <h3 class="text-primary">Rp {{ number_format($booking->total_amount,0,',','.') }}</h3>
            </div>
          </div>
          
          <div class="mt-3 card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Detail Pembayaran</h5>
              <p class="mb-1"><strong>Nomor invoice:</strong> {{ $payment->invoice_number ?? '-' }}</p>
              <p class="mb-1"><strong>Status pembayaran:</strong>
                @php
                  $status = $payment->payment_status ?? 'pending';
                @endphp
                @if($status === 'pending')
                  <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                @elseif($status === 'paid')
                  <span class="badge bg-success">Pembayaran Berhasil</span>
                @elseif($status === 'failed')
                  <span class="badge bg-danger">Pembayaran Gagal</span>
                @elseif($status === 'expired')
                  <span class="badge bg-secondary">Pembayaran Kedaluwarsa</span>
                @else
                  <span class="badge bg-info">{{ $status }}</span>
                @endif
              </p>
              <p class="mb-1"><strong>Batas waktu pembayaran:</strong>
                @if($payment && $payment->expired_at)
                  {{ $payment->expired_at->format('d M Y H:i') }}
                @else
                  -
                @endif
              </p>
              <p class="mb-1"><strong>Total tagihan:</strong> <span class="fs-4 text-danger">Rp {{ number_format(($payment->gross_amount ?? $booking->total_amount), 0, ',', '.') }}</span></p>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card shadow-sm mb-3">
            <div class="card-body">
              <h5 class="card-title">Pilih Metode Pembayaran</h5>
              <div class="row g-3">
                <div class="col-6">
                  <div class="card method-card payment-method p-3 text-center" data-method="qris">
                    <i class="fa-solid fa-qrcode fa-2x mb-2"></i>
                    <div>QRIS</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="card method-card payment-method p-3 text-center" data-method="va">
                    <i class="fa-solid fa-building-columns fa-2x mb-2"></i>
                    <div>Virtual Account</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="card method-card payment-method p-3 text-center" data-method="ewallet">
                    <i class="fa-solid fa-wallet fa-2x mb-2"></i>
                    <div>E-Wallet</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="card method-card payment-method p-3 text-center" data-method="card">
                    <i class="fa-solid fa-credit-card fa-2x mb-2"></i>
                    <div>Kartu Kredit/Debit</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="card method-card payment-method p-3 text-center" data-method="retail">
                    <i class="fa-solid fa-shop fa-2x mb-2"></i>
                    <div>Indomaret / Alfamart</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex gap-2">
            <button id="payNow" class="btn btn-primary flex-fill">Bayar Sekarang</button>
            <a href="#" id="cancelBooking" class="btn btn-outline-danger">Batalkan Booking</a>
          </div>
        </div>
      </div>

      <!-- Midtrans script -->
      @if(!empty($clientKey))
        <script src="{{ $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $clientKey }}"></script>
      @endif

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      <script>
        const bookingId = {{ $booking->id }};
        const payBtn = document.getElementById('payNow');
        const methodCards = document.querySelectorAll('.payment-method');

        async function startPayment() {
          if (payBtn.disabled) return;

          payBtn.disabled = true;
          payBtn.innerText = 'Membuat Transaksi...';

          try {
            const res = await fetch(`{{ url('/booking') }}/${bookingId}/pembayaran/snap`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value
              },
              body: JSON.stringify({})
            });

            const json = await res.json();
            if (json.error) {
              alert(json.error);
              payBtn.disabled = false;
              payBtn.innerText = 'Bayar Sekarang';
              return;
            }

            const token = json.token;
            window.snap.pay(token, {
              onSuccess: function(result){
                window.location = '{{ route('payments.success') }}';
              },
              onPending: function(result){
                window.location = '{{ route('payments.success') }}';
              },
              onError: function(result){
                window.location = '{{ route('payments.fail') }}';
              },
              onClose: function(){
                payBtn.disabled = false;
                payBtn.innerText = 'Bayar Sekarang';
              }
            });

          } catch (e) {
            alert('Gagal membuat transaksi. ' + e.message);
            payBtn.disabled = false;
            payBtn.innerText = 'Bayar Sekarang';
          }
        }

        payBtn.addEventListener('click', startPayment);
        methodCards.forEach(card => {
          card.addEventListener('click', startPayment);
        });
      </script>
    </div>
  </body>
</html>
