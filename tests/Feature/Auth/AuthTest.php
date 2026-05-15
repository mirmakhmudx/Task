<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ==================== REGISTER ====================

    public function test_registration_page_is_accessible(): void
    {
        $this->get(route('register'))
            ->assertOk();
    }

    public function test_user_can_register(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Ali',
            'email'                 => 'ali@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'ali@example.com',
        ]);
    }

    public function test_registration_requires_name(): void
    {
        $this->post(route('register'), [
            'email'                 => 'ali@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('name');
    }

    public function test_registration_requires_valid_email(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Ali',
            'email'                 => 'not-an-email',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Ali',
            'email'                 => 'ali@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'wrong',
        ])->assertSessionHasErrors('password');
    }

    // ==================== LOGIN ====================

    public function test_login_page_is_accessible(): void
    {
        $this->get(route('login'))
            ->assertOk();
    }

    public function test_active_user_can_login(): void
    {
        $user = User::factory()->active()->create([
            'email'    => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->post(route('login'), [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ])->assertRedirect();

        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_password_cannot_login(): void
    {
        User::factory()->active()->create([
            'email'    => 'test@example.com',
            'password' => bcrypt('correct'),
        ]);

        $this->post(route('login'), [
            'email'    => 'test@example.com',
            'password' => 'wrong',
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }

    public function test_waiting_user_cannot_login(): void
    {
        User::factory()->wait()->create([
            'email'    => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->post(route('login'), [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }

    // ==================== LOGOUT ====================

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->active()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect('/');

        $this->assertGuest();
    }

    // ==================== GUEST REDIRECTS ====================

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')
            ->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_cabinet(): void
    {
        $this->get(route('cabinet.adverts.index'))
            ->assertRedirect(route('login'));
    }
}
