<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id
            && in_array($booking->status, [Booking::STATUS_PENDING, 'menunggu pembayaran'], true);
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id && $booking->status === Booking::STATUS_PENDING;
    }
}
