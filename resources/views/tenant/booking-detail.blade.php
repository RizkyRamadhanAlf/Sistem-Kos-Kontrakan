@extends('tenant.layout')

@section('title', 'Detail Booking - KostKu')

@section('content')
@php
    $paymentStatus = $booking->payment?->payment_status;
    $canCancel = in_array($booking->status, [\App\Models\Booking::STATUS_PENDING, 'menunggu pembayaran'], true)
        && (!$booking->payment || in_array($paymentStatus, [\App\Models\Payment::STATUS_PENDING, 'unpaid', null], true));
    $statusBadges = [
        \App\Models\Booking::STATUS_PENDING => ['warning', 'Menunggu Pembayaran'],
        \App\Models\Booking::STATUS_PAID => ['success', 'Paid'],
        'confirmed' => ['primary', 'Confirmed'],
        'active' => ['primary', 'Active'],
        \App\Models\Booking::STATUS_CANCELLED => ['danger', 'Cancelled'],
        \App\Models\Booking::STATUS_EXPIRED => ['secondary', 'Expired'],
        \App\Models\Booking::STATUS_COMPLETED => ['info', 'Completed'],
    ];
    [$badgeColor, $badgeLabel] = $statusBadges[$booking->status] ?? ['secondary', ucfirst($booking->status)];
@endphp

<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('tenant.bookings') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-chevron-left"></i> Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <!-- Detail Kos -->
        <div style="background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
            <img src="https://via.placeholder.com/600x300" style="width: 100%; height: 300px; object-fit: cover;">
            <div style="padding: 1.5rem;">
                <h5 style="margin: 0 0 0.5rem; font-weight: 700;">{{ $booking->kos_name }}</h5>
                <p style="margin: 0; color: #64748b;">
                    <i class="bi bi-geo-fill"></i> {{ $booking->location }}
                </p>
                <hr>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Nomor Kamar</p>
                        <p style="margin: 0.25rem 0 0; font-weight: 600; font-size: 1.1rem;">{{ $booking->room_type }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Tipe Kamar</p>
                        <p style="margin: 0.25rem 0 0; font-weight: 600; font-size: 1.1rem;">{{ $booking->room_type }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Booking -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
            <h6 style="margin: 0 0 1.5rem; font-weight: 700;">Rincian Booking</h6>

            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 1rem 0; color: #64748b;">Tanggal Booking</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 600;">{{ $booking->booking_date?->format('d M Y') ?? '-' }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 1rem 0; color: #64748b;">Durasi Sewa</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 600;">{{ $booking->duration_months }} bulan</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 1rem 0; color: #64748b;">Harga per Bulan</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 600;">Rp {{ number_format($booking->price_per_month, 0, ',', '.') }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 1rem 0; color: #64748b;">Biaya Admin</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 600;">Rp {{ number_format($booking->admin_fee, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 1rem 0; color: #64748b; font-weight: 700;">Total Pembayaran</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 700; color: var(--primary); font-size: 1.1rem;">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Status -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
            <h6 style="margin: 0 0 1rem; font-weight: 700;">Status Booking</h6>
            <div style="text-align: center; padding: 1.5rem; background: var(--light); border-radius: 8px;">
                <span class="badge bg-{{ $badgeColor }}" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                    {{ $badgeLabel }}
                </span>
            </div>
        </div>

        <!-- Aksi -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
            <h6 style="margin: 0 0 1rem; font-weight: 700;">Aksi</h6>
            
            <div class="d-flex flex-wrap gap-2">
            @if($canCancel)
                <a href="{{ route('booking.payment.show', $booking) }}" class="btn btn-primary flex-fill">
                    <i class="bi bi-credit-card-fill"></i> Bayar Sekarang
                </a>

                <form id="cancel-booking-form" action="{{ route('booking.cancel', $booking) }}" method="POST" class="flex-fill">
                    @csrf
                    <button type="button" id="cancel-booking-button" class="btn btn-danger w-100">
                        <i class="bi bi-x-circle-fill"></i> Batalkan Booking
                    </button>
                </form>
            @endif

            <a href="{{ route('tenant.bookings') }}" class="btn btn-outline-secondary flex-fill">
                <i class="bi bi-chevron-left"></i> Kembali
            </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('cancel-booking-button')?.addEventListener('click', () => {
        Swal.fire({
            title: 'Batalkan Booking?',
            html: 'Apakah Anda yakin ingin membatalkan booking ini?<br>Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak',
            reverseButtons: true,
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('cancel-booking-form').submit();
            }
        });
    });
</script>
@endpush
