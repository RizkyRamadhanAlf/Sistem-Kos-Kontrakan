<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutingTest extends TestCase
{
    public function test_routing_works(): void
    {
        $response = $this->get('/test-routing');

        $response->assertStatus(200);
        $response->assertSee('Routing works!');
    }
}
