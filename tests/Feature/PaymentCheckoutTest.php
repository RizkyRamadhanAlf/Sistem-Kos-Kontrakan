<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_opening_checkout_creates_pending_invoice_with_deadline(): void
    {
        $tenant = User::factory()->create(['role' => 'penyewa']);
        $booking = Booking::create([
            'user_id' => $tenant->id,
            'kos_name' => 'Kos Premium',
            'location' => 'Jakarta',
            'tenant_name' => $tenant->name,
            'booking_date' => now(),
            'duration_months' => 3,
            'price_per_month' => 1500000,
            'admin_fee' => 25000,
            'total_amount' => 4525000,
            'status' => Booking::STATUS_PENDING,
        ]);

        $this->actingAs($tenant)
            ->get(route('booking.payment.show', $booking))
            ->assertOk()
            ->assertSee('Selesaikan Pembayaran')
            ->assertSee('Pembayaran Aman &amp; Terpercaya', false)
            ->assertSee('Metode pembayaran yang didukung')
            ->assertDontSee('Pilih Metode Pembayaran')
            ->assertDontSee('data-method=');

        $payment = Payment::where('booking_id', $booking->id)->firstOrFail();
        $this->assertSame(Payment::STATUS_PENDING, $payment->payment_status);
        $this->assertNotNull($payment->invoice_number);
        $this->assertNotNull($payment->expired_at);
    }

    public function test_tenant_can_cancel_pending_booking_from_checkout(): void
    {
        $tenant = User::factory()->create(['role' => 'penyewa']);
        $booking = Booking::create([
            'user_id' => $tenant->id,
            'kos_name' => 'Kos Premium',
            'tenant_name' => $tenant->name,
            'duration_months' => 1,
            'status' => Booking::STATUS_PENDING,
        ]);

        $this->actingAs($tenant)
            ->post(route('booking.cancel', $booking))
            ->assertRedirect(route('tenant.booking-detail', $booking));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_CANCELLED,
        ]);
    }

    public function test_checkout_marks_expired_payment_and_booking_as_expired(): void
    {
        $tenant = User::factory()->create(['role' => 'penyewa']);
        $booking = Booking::create([
            'user_id' => $tenant->id,
            'kos_name' => 'Kos Premium',
            'tenant_name' => $tenant->name,
            'duration_months' => 1,
            'status' => Booking::STATUS_PENDING,
        ]);
        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $tenant->id,
            'invoice_number' => 'INV-EXPIRED',
            'tenant_name' => $tenant->name,
            'amount' => 1000000,
            'gross_amount' => 1000000,
            'payment_date' => now(),
            'payment_status' => Payment::STATUS_PENDING,
            'status' => Payment::STATUS_PENDING,
            'expired_at' => now()->subMinute(),
        ]);

        $this->actingAs($tenant)->get(route('booking.payment.show', $booking))->assertOk();

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => Booking::STATUS_EXPIRED]);
        $this->assertDatabaseHas('payments', ['booking_id' => $booking->id, 'payment_status' => Payment::STATUS_EXPIRED]);
    }

    public function test_countdown_expiry_endpoint_updates_pending_payment(): void
    {
        $tenant = User::factory()->create(['role' => 'penyewa']);
        $booking = Booking::create([
            'user_id' => $tenant->id,
            'kos_name' => 'Kos Premium',
            'tenant_name' => $tenant->name,
            'duration_months' => 1,
            'status' => Booking::STATUS_PENDING,
        ]);
        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $tenant->id,
            'invoice_number' => 'INV-COUNTDOWN',
            'tenant_name' => $tenant->name,
            'amount' => 1000000,
            'payment_date' => now(),
            'payment_status' => Payment::STATUS_PENDING,
            'status' => Payment::STATUS_PENDING,
            'expired_at' => now()->subSecond(),
        ]);

        $this->actingAs($tenant)
            ->post(route('booking.expire', $booking))
            ->assertNoContent();

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => Booking::STATUS_EXPIRED]);
    }
}
