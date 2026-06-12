<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
}
