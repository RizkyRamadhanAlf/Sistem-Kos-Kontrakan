<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

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
            'duration_months' => 'required|integer|min:1',
            'price_per_month' => 'required|numeric|min:0',
            'booking_date' => 'required|date',
        ]);

        $adminFee = 5000; // Contoh biaya admin tetap
        $totalAmount = ($validated['price_per_month'] * $validated['duration_months']) + $adminFee;

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'kos_name' => $validated['kos_name'],
            'room_type' => $validated['room_type'],
            'location' => $validated['location'],
            'tenant_name' => $validated['tenant_name'],
            'booking_date' => $validated['booking_date'],
            'duration_months' => $validated['duration_months'],
            'price_per_month' => $validated['price_per_month'],
            'admin_fee' => $adminFee,
            'total_amount' => $totalAmount,
            'status' => Booking::STATUS_PENDING,
        ]);

        return redirect()->route('booking.payment.show', $booking->id);
    }
}
