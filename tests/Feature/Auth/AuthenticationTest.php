<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_landing_page_shows_guest_actions_to_guests(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Login')
            ->assertSee('Daftar')
            ->assertDontSee('Logout');
    }

    public function test_landing_page_shows_dashboard_and_logout_to_authenticated_users(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('landing'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Logout');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create(['role' => 'penyewa']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('tenant.dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response
            ->assertRedirect(route('landing', absolute: false))
            ->assertSessionHas('status', 'Anda berhasil logout.');
    }

    public function test_users_are_redirected_to_their_role_dashboard_after_login(): void
    {
        foreach ([
            'tenant' => 'tenant.dashboard',
            'owner' => 'dashboard.owner',
            'admin' => 'dashboard.admin',
        ] as $role => $route) {
            $user = User::factory()->create(['role' => $role]);

            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

            $response->assertRedirect(route($route, absolute: false));
            $this->post('/logout');
        }
    }
}
