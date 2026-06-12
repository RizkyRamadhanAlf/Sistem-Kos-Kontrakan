<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_open_dashboard_with_owned_business_data(): void
    {
        [$owner, $property] = $this->createOwnerProperty();

        $this->actingAs($owner)
            ->get(route('dashboard.owner'))
            ->assertOk()
            ->assertSee('Ringkasan Bisnis')
            ->assertSee($property->name)
            ->assertSee('Pendapatan Bulan Ini');
    }

    public function test_tenant_cannot_open_owner_dashboard(): void
    {
        $tenant = User::factory()->create(['role' => 'tenant']);

        $this->actingAs($tenant)->get(route('dashboard.owner'))->assertForbidden();
    }

    public function test_owner_can_approve_booking_and_room_becomes_occupied(): void
    {
        [$owner, $property] = $this->createOwnerProperty();
        $tenant = User::factory()->create(['role' => 'tenant']);
        $room = $property->rooms()->create([
            'room_number' => '101', 'room_type' => 'Deluxe', 'price_per_month' => 1500000, 'status' => Room::STATUS_BOOKED,
        ]);
        $booking = Booking::create([
            'room_id' => $room->id, 'user_id' => $tenant->id, 'kos_name' => $property->name,
            'tenant_name' => $tenant->name, 'status' => Booking::STATUS_PENDING,
        ]);

        $this->actingAs($owner)
            ->patchJson(route('owner.bookings.status', $booking), ['status' => Booking::STATUS_APPROVED])
            ->assertOk();

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => Booking::STATUS_APPROVED]);
        $this->assertDatabaseHas('rooms', ['id' => $room->id, 'status' => Room::STATUS_OCCUPIED]);
    }

    public function test_owner_cannot_manage_another_owners_booking(): void
    {
        [$owner] = $this->createOwnerProperty();
        [, $otherProperty] = $this->createOwnerProperty();
        $room = $otherProperty->rooms()->create([
            'room_number' => '202', 'room_type' => 'Standard', 'price_per_month' => 1000000, 'status' => Room::STATUS_BOOKED,
        ]);
        $booking = Booking::create(['room_id' => $room->id, 'kos_name' => $otherProperty->name, 'status' => Booking::STATUS_PENDING]);

        $this->actingAs($owner)
            ->patchJson(route('owner.bookings.status', $booking), ['status' => Booking::STATUS_REJECTED])
            ->assertForbidden();
    }

    public function test_owner_can_create_property_with_description_facilities_and_rules(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $this->actingAs($owner)->post(route('owner.properties.store'), [
            'name' => 'Kos Informasi Lengkap',
            'location' => 'Jakarta',
            'status' => 'active',
            'description' => 'Kos nyaman dan aman.',
            'facilities' => ['WiFi', 'AC', 'Keamanan 24 Jam'],
            'rules' => "- Menjaga kebersihan.\n- Tidak membuat keributan.",
        ])->assertRedirect();

        $property = Property::where('owner_id', $owner->id)->firstOrFail();
        $this->assertSame(['WiFi', 'AC', 'Keamanan 24 Jam'], $property->facilities);
        $this->assertSame('Kos nyaman dan aman.', $property->description);
        $this->assertStringContainsString('Tidak membuat keributan', $property->rules);
    }

    public function test_owner_can_update_own_property_details_but_not_another_owners_property(): void
    {
        [$owner, $property] = $this->createOwnerProperty();
        [, $otherProperty] = $this->createOwnerProperty();
        $payload = [
            'name' => 'Kos Diperbarui', 'location' => 'Bandung', 'status' => 'active',
            'description' => 'Deskripsi baru.', 'facilities' => ['WiFi', 'Laundry'], 'rules' => 'Aturan baru.',
        ];

        $this->actingAs($owner)->put(route('owner.properties.update', $property), $payload)->assertRedirect();
        $property->refresh();
        $this->assertSame('Deskripsi baru.', $property->description);
        $this->assertSame(['WiFi', 'Laundry'], $property->facilities);

        $this->actingAs($owner)->put(route('owner.properties.update', $otherProperty), $payload)->assertForbidden();
    }

    private function createOwnerProperty(): array
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $property = Property::create([
            'owner_id' => $owner->id, 'name' => 'Kos Owner '.$owner->id, 'location' => 'Jakarta', 'status' => 'active',
        ]);

        return [$owner, $property];
    }
}
