<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_can_download_their_invoice_as_pdf(): void
    {
        [$tenant, $payment] = $this->createPayment();

        $response = $this->actingAs($tenant)
            ->get(route('tenant.invoice.download', $payment));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertDownload('inv-test-001.pdf');

        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_tenant_cannot_download_another_tenants_invoice(): void
    {
        [, $payment] = $this->createPayment();
        $otherTenant = User::factory()->create(['role' => 'penyewa']);

        $this->actingAs($otherTenant)
            ->get(route('tenant.invoice.download', $payment))
            ->assertForbidden();
    }

    private function createPayment(): array
    {
        $tenant = User::factory()->create(['role' => 'penyewa']);
        $booking = Booking::create([
            'user_id' => $tenant->id,
            'kos_name' => 'Kost Harmoni',
            'room_type' => 'Deluxe - A01',
            'location' => 'Jakarta',
            'tenant_name' => $tenant->name,
            'booking_date' => now(),
            'duration_months' => 2,
            'price_per_month' => 1500000,
            'admin_fee' => 25000,
            'total_amount' => 3025000,
            'status' => Booking::STATUS_PAID,
            'check_in_date' => now()->addWeek(),
            'check_out_date' => now()->addMonths(2)->addWeek(),
        ]);
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $tenant->id,
            'invoice_number' => 'INV-TEST-001',
            'tenant_name' => $tenant->name,
            'gross_amount' => 3025000,
            'amount' => 3025000,
            'payment_date' => now(),
            'payment_method' => 'bank_transfer',
            'payment_status' => Payment::STATUS_PAID,
            'status' => Payment::STATUS_PAID,
            'paid_at' => now(),
        ]);

        return [$tenant, $payment];
    }
}
