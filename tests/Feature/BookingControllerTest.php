<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_form_duration_string_is_cast_before_carbon_calculation(): void
    {
        $tenant = User::factory()->create(['role' => 'penyewa']);
        $checkInDate = now()->addDay()->toDateString();

        $this->actingAs($tenant)->post(route('booking.store'), [
            'kos_name' => 'Kos Uji',
            'room_type' => 'Standar',
            'location' => 'Jakarta',
            'tenant_name' => $tenant->name,
            'duration_months' => '6',
            'price_per_month' => '1000000',
            'booking_date' => $checkInDate,
        ])->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'user_id' => $tenant->id,
            'duration_months' => 6,
            'total_amount' => 6005000,
        ]);

        $booking = Booking::where('user_id', $tenant->id)->firstOrFail();
        $this->assertSame($checkInDate, $booking->check_in_date->toDateString());
        $this->assertSame(
            now()->addDay()->addMonths(6)->toDateString(),
            $booking->check_out_date->toDateString()
        );
    }

    public function test_booking_duration_must_be_a_positive_integer(): void
    {
        $tenant = User::factory()->create(['role' => 'penyewa']);

        $this->actingAs($tenant)->post(route('booking.store'), [
            'kos_name' => 'Kos Uji',
            'room_type' => 'Standar',
            'location' => 'Jakarta',
            'tenant_name' => $tenant->name,
            'duration_months' => '1.5',
            'price_per_month' => '1000000',
            'booking_date' => now()->addDay()->toDateString(),
        ])->assertSessionHasErrors('duration_months');
    }
}
