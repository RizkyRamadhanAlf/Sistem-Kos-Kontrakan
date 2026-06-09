<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_can_open_dashboard(): void
    {
        $tenant = User::factory()->create(['role' => 'penyewa']);

        $this->actingAs($tenant)->get(route('tenant.dashboard'))
            ->assertOk()
            ->assertSee('Selamat Datang');
    }

    public function test_tenant_cannot_view_another_users_booking(): void
    {
        $tenant = User::factory()->create(['role' => 'penyewa']);
        $otherTenant = User::factory()->create(['role' => 'penyewa']);
        $booking = Booking::create([
            'user_id' => $otherTenant->id,
            'kos_name' => 'Kos Privat',
            'tenant_name' => $otherTenant->name,
            'duration_months' => 1,
            'status' => Booking::STATUS_PENDING,
        ]);

        $this->actingAs($tenant)->get(route('tenant.booking-detail', $booking))
            ->assertForbidden();
    }

    public function test_tenant_can_create_booking_for_available_room(): void
    {
        $tenant = User::factory()->create(['role' => 'penyewa']);
        $owner = User::factory()->create(['role' => 'tenant']);
        $property = Property::create([
            'owner_id' => $owner->id,
            'name' => 'Kos Uji',
            'location' => 'Jakarta',
            'status' => 'active',
        ]);
        $room = Room::create([
            'property_id' => $property->id,
            'room_number' => '101',
            'room_type' => 'Standar',
            'price_per_month' => 1000000,
            'status' => 'available',
        ]);

        $this->actingAs($tenant)->post(route('tenant.booking.create', $property), [
            'room_id' => $room->id,
            'check_in_date' => now()->addDay()->toDateString(),
            'duration_months' => '3',
        ])->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'user_id' => $tenant->id,
            'room_id' => $room->id,
            'total_amount' => 3025000,
            'status' => Booking::STATUS_PENDING,
        ]);

        $booking = Booking::where('user_id', $tenant->id)->firstOrFail();
        $this->assertSame(
            now()->addDay()->addMonths(3)->toDateString(),
            $booking->check_out_date->toDateString()
        );
    }

    public function test_tenant_booking_duration_must_be_a_positive_integer(): void
    {
        $tenant = User::factory()->create(['role' => 'penyewa']);
        $owner = User::factory()->create(['role' => 'tenant']);
        $property = Property::create([
            'owner_id' => $owner->id,
            'name' => 'Kos Uji Validasi',
            'location' => 'Jakarta',
            'status' => 'active',
        ]);
        $room = Room::create([
            'property_id' => $property->id,
            'room_number' => '102',
            'room_type' => 'Standar',
            'price_per_month' => 1000000,
            'status' => 'available',
        ]);

        $this->actingAs($tenant)->post(route('tenant.booking.create', $property), [
            'room_id' => $room->id,
            'check_in_date' => now()->addDay()->toDateString(),
            'duration_months' => '2.5',
        ])->assertSessionHasErrors('duration_months');

        $this->assertDatabaseCount('bookings', 0);
    }
}
