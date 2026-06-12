<?php

namespace Tests\Feature;

use App\Http\Controllers\PaymentController;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransPaymentStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_midtrans_notification_marks_payment_booking_and_room_as_paid(): void
    {
        [, $payment, $booking, $room] = $this->createPendingPayment();
        config(['midtrans.server_key' => 'server-key']);

        $payload = [
            'order_id' => $payment->order_id,
            'transaction_status' => 'settlement',
            'status_code' => '200',
            'gross_amount' => '1025000.00',
            'payment_type' => 'bank_transfer',
        ];
        $payload['signature_key'] = $this->signature($payload, 'server-key');

        $this->postJson(route('midtrans.notification'), $payload)
            ->assertOk();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => Payment::STATUS_PAID,
            'status' => Payment::STATUS_PAID,
            'payment_method' => 'bank_transfer',
        ]);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_PAID,
        ]);
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'status' => Room::STATUS_BOOKED,
        ]);
    }

    public function test_midtrans_notification_rejects_invalid_signature(): void
    {
        [, $payment] = $this->createPendingPayment();
        config(['midtrans.server_key' => 'server-key']);

        $this->postJson(route('midtrans.notification'), [
            'order_id' => $payment->order_id,
            'transaction_status' => 'settlement',
            'status_code' => '200',
            'gross_amount' => '1025000.00',
            'signature_key' => 'invalid',
        ])->assertForbidden();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => Payment::STATUS_PENDING,
        ]);
    }

    public function test_paid_payment_is_not_downgraded_by_later_notification(): void
    {
        [, $payment] = $this->createPendingPayment();
        $payment->update(['payment_status' => Payment::STATUS_PAID, 'status' => Payment::STATUS_PAID]);
        config(['midtrans.server_key' => 'server-key']);

        $payload = [
            'order_id' => $payment->order_id,
            'transaction_status' => 'expire',
            'status_code' => '407',
            'gross_amount' => '1025000.00',
        ];
        $payload['signature_key'] = $this->signature($payload, 'server-key');

        $this->postJson(route('midtrans.notification'), $payload)->assertOk();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => Payment::STATUS_PAID,
        ]);
    }

    public function test_only_payment_owner_can_check_midtrans_status(): void
    {
        [, $payment] = $this->createPendingPayment();
        $otherTenant = User::factory()->create(['role' => 'tenant']);

        $this->actingAs($otherTenant)
            ->get(route('payment.check-status', $payment))
            ->assertForbidden();
    }

    public function test_paid_payment_check_redirects_with_success_without_calling_midtrans(): void
    {
        [$tenant, $payment] = $this->createPendingPayment();
        $payment->update(['payment_status' => Payment::STATUS_PAID, 'status' => Payment::STATUS_PAID]);

        $this->actingAs($tenant)
            ->get(route('payment.check-status', $payment))
            ->assertRedirect(route('tenant.payment-detail', $payment))
            ->assertSessionHas('success', 'Pembayaran berhasil.');
    }

    public function test_owner_check_status_updates_payment_booking_and_room_from_midtrans(): void
    {
        [$tenant, $payment, $booking, $room] = $this->createPendingPayment();

        $this->app->bind(PaymentController::class, fn () => new class extends PaymentController
        {
            protected function configureMidtrans(): void
            {
                //
            }

            protected function getMidtransStatus(string $orderId): object
            {
                return (object) [
                    'transaction_status' => 'settlement',
                    'payment_type' => 'qris',
                    'gross_amount' => '1025000.00',
                ];
            }
        });

        $this->actingAs($tenant)
            ->get(route('payment.check-status', $payment))
            ->assertRedirect(route('tenant.payment-detail', $payment))
            ->assertSessionHas('success', 'Pembayaran berhasil.');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => Payment::STATUS_PAID,
            'payment_method' => 'qris',
        ]);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_PAID,
        ]);
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'status' => Room::STATUS_BOOKED,
        ]);
    }

    private function createPendingPayment(): array
    {
        $tenant = User::factory()->create(['role' => 'tenant']);
        $owner = User::factory()->create(['role' => 'owner']);
        $property = Property::create([
            'owner_id' => $owner->id,
            'name' => 'Kos Midtrans',
            'location' => 'Jakarta',
            'status' => 'active',
        ]);
        $room = Room::create([
            'property_id' => $property->id,
            'room_number' => '101',
            'room_type' => 'Standar',
            'price_per_month' => 1000000,
            'status' => Room::STATUS_AVAILABLE,
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
            'invoice_number' => 'INV-MIDTRANS-001',
            'order_id' => 'ORDER-MIDTRANS-001',
            'tenant_name' => $tenant->name,
            'gross_amount' => 1025000,
            'amount' => 1025000,
            'payment_date' => now(),
            'payment_status' => Payment::STATUS_PENDING,
            'status' => Payment::STATUS_PENDING,
        ]);

        return [$tenant, $payment, $booking, $room];
    }

    private function signature(array $payload, string $serverKey): string
    {
        return hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].$serverKey);
    }
}
