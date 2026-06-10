<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567891',
            'address' => 'Test Address',
            'role' => 'tenant',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $response
            ->assertRedirect(route('login', absolute: false))
            ->assertSessionHas('status', 'Registrasi berhasil. Silakan login menggunakan akun Anda.');

        $this->get(route('landing'))
            ->assertSee('Login')
            ->assertSee('Daftar');
    }
}
