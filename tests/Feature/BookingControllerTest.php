<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Room;
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

    public function test_owner_can_cancel_pending_booking_and_release_booked_room(): void
    {
        [$tenant, $booking, $payment, $room] = $this->createPendingBooking();

        $this->actingAs($tenant)
            ->post(route('booking.cancel', $booking))
            ->assertRedirect(route('tenant.booking-detail', $booking))
            ->assertSessionHas('success', 'Booking berhasil dibatalkan.');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_CANCELLED,
        ]);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => Payment::STATUS_CANCELLED,
            'status' => Payment::STATUS_CANCELLED,
        ]);
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'status' => Room::STATUS_AVAILABLE,
        ]);
    }

    public function test_user_cannot_cancel_another_users_booking(): void
    {
        [, $booking] = $this->createPendingBooking();
        $otherTenant = User::factory()->create(['role' => 'tenant']);

        $this->actingAs($otherTenant)
            ->post(route('booking.cancel', $booking))
            ->assertForbidden();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_PENDING,
        ]);
    }

    public function test_paid_booking_cannot_be_cancelled(): void
    {
        [$tenant, $booking] = $this->createPendingBooking();
        $booking->update(['status' => Booking::STATUS_PAID]);

        $this->actingAs($tenant)
            ->post(route('booking.cancel', $booking))
            ->assertForbidden();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_PAID,
        ]);
    }

    public function test_booking_with_paid_payment_cannot_be_cancelled(): void
    {
        [$tenant, $booking, $payment, $room] = $this->createPendingBooking();
        $payment->update([
            'payment_status' => Payment::STATUS_PAID,
            'status' => Payment::STATUS_PAID,
        ]);

        $this->actingAs($tenant)
            ->post(route('booking.cancel', $booking))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_PENDING,
        ]);
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'status' => Room::STATUS_BOOKED,
        ]);
    }

    public function test_cancel_button_only_appears_for_pending_unpaid_booking(): void
    {
        [$tenant, $booking, $payment] = $this->createPendingBooking();

        $this->actingAs($tenant)
            ->get(route('tenant.booking-detail', $booking))
            ->assertOk()
            ->assertSee('Batalkan Booking')
            ->assertSee('id="cancel-booking-button"', false)
            ->assertSee('Batalkan Booking?');

        $payment->update([
            'payment_status' => Payment::STATUS_PAID,
            'status' => Payment::STATUS_PAID,
        ]);
        $booking->update(['status' => Booking::STATUS_PAID]);

        $this->actingAs($tenant)
            ->get(route('tenant.booking-detail', $booking))
            ->assertOk()
            ->assertDontSee('id="cancel-booking-button"', false);
    }

    private function createPendingBooking(): array
    {
        $tenant = User::factory()->create(['role' => 'tenant']);
        $owner = User::factory()->create(['role' => 'owner']);
        $property = Property::create([
            'owner_id' => $owner->id,
            'name' => 'Kos Pembatalan',
            'location' => 'Jakarta',
            'status' => 'active',
        ]);
        $room = Room::create([
            'property_id' => $property->id,
            'room_number' => '101',
            'room_type' => 'Standar',
            'price_per_month' => 1000000,
            'status' => Room::STATUS_BOOKED,
        ]);
        $booking = Booking::create([
            'room_id' => $room->id,
            'user_id' => $tenant->id,
            'kos_name' => $property->name,
            'tenant_name' => $tenant->name,
            'duration_months' => 1,
            'total_amount' => 1025000,
            'status' => Booking::STATUS_PENDING,
        ]);
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $tenant->id,
            'invoice_number' => 'INV-CANCEL-001',
            'tenant_name' => $tenant->name,
            'amount' => 1025000,
            'payment_date' => now(),
            'payment_status' => Payment::STATUS_PENDING,
            'status' => Payment::STATUS_PENDING,
        ]);

        return [$tenant, $booking, $payment, $room];
    }
}
