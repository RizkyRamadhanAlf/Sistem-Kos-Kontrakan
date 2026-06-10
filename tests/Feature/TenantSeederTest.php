<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_demo_data_can_be_seeded(): void
    {
        $this->seed();

        $tenant = User::where('email', 'ahmad@student.com')->firstOrFail();

        $this->assertGreaterThanOrEqual(6, Property::count());
        $this->assertTrue(Booking::where('user_id', $tenant->id)->exists());
        $this->assertTrue(Notification::where('user_id', $tenant->id)->exists());
    }
}
