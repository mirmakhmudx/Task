<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_form_is_displayed(): void
    {
        $response = $this->get('/login');

        $response
            ->assertStatus(200)
            ->assertSee('Email');
    }

    public function test_login_fails_with_empty_credentials(): void
    {
        $response = $this->post('/login', [
            'email'    => '',
            'password' => '',
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email'  => 'user@example.com',
            'status' => User::STATUS_ACTIVE,
        ]);

        $response = $this->post('/login', [
            'email'    => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_active_user_can_login(): void
    {
        $user = User::factory()->create([
            'status' => User::STATUS_ACTIVE,
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_waiting_user_cannot_login(): void
    {
        $user = User::factory()->wait()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_from_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
