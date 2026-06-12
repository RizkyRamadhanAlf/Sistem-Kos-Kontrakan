<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Complaint;
use App\Models\Notification;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ComplaintFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_can_create_complaint_for_active_booking_with_images(): void
    {
        Storage::fake('public');
        [$tenant, $owner, $booking] = $this->activeBooking();
        $image = UploadedFile::fake()->createWithContent(
            'bukti.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')
        );

        $this->actingAs($tenant)->post(route('tenant.complaints.store'), [
            'booking_id' => $booking->id,
            'category' => 'facility_damage',
            'priority' => 'high',
            'title' => 'AC kamar tidak berfungsi',
            'description' => 'AC kamar sudah tidak dingin sejak kemarin.',
            'images' => [$image],
        ])->assertRedirect();

        $complaint = Complaint::firstOrFail();
        $this->assertSame($tenant->id, $complaint->user_id);
        $this->assertSame($owner->id, $complaint->owner_id);
        $this->assertSame(Complaint::STATUS_PENDING, $complaint->status);
        Storage::disk('public')->assertExists($complaint->images()->firstOrFail()->image_path);
        $this->assertDatabaseHas('notifications', ['user_id' => $tenant->id, 'complaint_id' => $complaint->id]);
        $this->assertDatabaseHas('notifications', ['user_id' => $owner->id, 'complaint_id' => $complaint->id]);
    }

    public function test_tenant_cannot_create_complaint_for_pending_booking(): void
    {
        [$tenant, , $booking] = $this->activeBooking(Booking::STATUS_PENDING);

        $this->actingAs($tenant)->post(route('tenant.complaints.store'), [
            'booking_id' => $booking->id,
            'category' => 'cleanliness',
            'priority' => 'medium',
            'title' => 'Kamar kurang bersih',
            'description' => 'Area kamar perlu dibersihkan kembali.',
        ])->assertNotFound();

        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_owner_can_reply_and_update_status(): void
    {
        [$tenant, $owner, $booking] = $this->activeBooking();
        $complaint = Complaint::create([
            'ticket_number' => 'KMP-TEST-001',
            'user_id' => $tenant->id,
            'owner_id' => $owner->id,
            'booking_id' => $booking->id,
            'category' => 'internet',
            'priority' => 'urgent',
            'title' => 'WiFi mati',
            'description' => 'Internet kamar tidak bisa digunakan.',
            'status' => Complaint::STATUS_PENDING,
        ]);

        $this->actingAs($owner)->post(route('owner.complaints.reply', $complaint), [
            'message' => 'Teknisi akan datang besok pukul 10.00 WIB.',
        ])->assertRedirect();

        $this->actingAs($owner)->patch(route('owner.complaints.status', $complaint), [
            'status' => Complaint::STATUS_IN_PROGRESS,
        ])->assertRedirect();

        $this->assertDatabaseHas('complaint_replies', [
            'complaint_id' => $complaint->id,
            'user_id' => $owner->id,
            'message' => 'Teknisi akan datang besok pukul 10.00 WIB.',
        ]);
        $this->assertDatabaseHas('complaints', ['id' => $complaint->id, 'status' => Complaint::STATUS_IN_PROGRESS]);
        $this->assertGreaterThanOrEqual(2, Notification::where('user_id', $tenant->id)->where('complaint_id', $complaint->id)->count());
    }

    public function test_other_owner_cannot_view_or_update_complaint(): void
    {
        [$tenant, $owner, $booking] = $this->activeBooking();
        $otherOwner = User::factory()->create(['role' => 'owner']);
        $complaint = Complaint::create([
            'ticket_number' => 'KMP-TEST-002',
            'user_id' => $tenant->id,
            'owner_id' => $owner->id,
            'booking_id' => $booking->id,
            'category' => 'water',
            'priority' => 'low',
            'title' => 'Air kecil',
            'description' => 'Tekanan air kamar mandi kecil.',
            'status' => Complaint::STATUS_PENDING,
        ]);

        $this->actingAs($otherOwner)->get(route('owner.complaints.show', $complaint))->assertForbidden();
        $this->actingAs($otherOwner)->patch(route('owner.complaints.status', $complaint), [
            'status' => Complaint::STATUS_RESOLVED,
        ])->assertForbidden();
    }

    private function activeBooking(string $status = Booking::STATUS_PAID): array
    {
        $tenant = User::factory()->create(['role' => 'tenant']);
        $owner = User::factory()->create(['role' => 'owner']);
        $property = Property::create([
            'owner_id' => $owner->id,
            'name' => 'Kos Melati',
            'location' => 'Jakarta',
            'status' => 'active',
        ]);
        $room = Room::create([
            'property_id' => $property->id,
            'room_number' => 'A01',
            'room_type' => 'Deluxe',
            'price_per_month' => 1500000,
            'status' => Room::STATUS_OCCUPIED,
        ]);
        $booking = Booking::create([
            'user_id' => $tenant->id,
            'room_id' => $room->id,
            'kos_name' => $property->name,
            'room_type' => 'Deluxe - A01',
            'tenant_name' => $tenant->name,
            'duration_months' => 1,
            'status' => $status,
        ]);

        return [$tenant, $owner, $booking];
    }
}
