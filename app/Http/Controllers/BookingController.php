<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create()
    {
        return view('booking.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kos_name' => 'required|string|max:255',
            'room_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'tenant_name' => 'required|string|max:255',
            'duration_months' => 'required|integer|min:1|max:24',
            'price_per_month' => 'required|numeric|min:0',
            'booking_date' => 'required|date|after_or_equal:today',
        ]);

        $durationMonths = (int) $validated['duration_months'];
        $pricePerMonth = (int) $validated['price_per_month'];
        $checkInDate = Carbon::parse($validated['booking_date']);
        $adminFee = 5000;
        $totalAmount = ($pricePerMonth * $durationMonths) + $adminFee;

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'kos_name' => $validated['kos_name'],
            'room_type' => $validated['room_type'],
            'location' => $validated['location'],
            'tenant_name' => $validated['tenant_name'],
            'booking_date' => $validated['booking_date'],
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkInDate->copy()->addMonths($durationMonths),
            'duration_months' => $durationMonths,
            'price_per_month' => $pricePerMonth,
            'admin_fee' => $adminFee,
            'total_amount' => $totalAmount,
            'status' => Booking::STATUS_PENDING,
        ]);

        return redirect()->route('booking.payment.show', $booking);
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('update', $booking);

        $booking->load('payment', 'room');
        $paymentStatus = $booking->payment?->payment_status;

        if (! in_array($booking->status, [Booking::STATUS_PENDING, 'menunggu pembayaran'], true)) {
            return back()->with('error', 'Booking yang sudah diproses tidak dapat dibatalkan.');
        }

        if ($booking->payment && ! in_array($paymentStatus, [Payment::STATUS_PENDING, 'unpaid', null], true)) {
            return back()->with('error', 'Booking dengan pembayaran yang sudah diproses tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($booking): void {
            $booking->update(['status' => Booking::STATUS_CANCELLED]);

            if ($booking->payment) {
                $booking->payment->update([
                    'payment_status' => Payment::STATUS_CANCELLED,
                    'status' => Payment::STATUS_CANCELLED,
                ]);
            }

            if ($booking->room?->status === Room::STATUS_BOOKED) {
                $booking->room->update(['status' => Room::STATUS_AVAILABLE]);
            }
        });

        return redirect()
            ->route('tenant.booking-detail', $booking)
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
