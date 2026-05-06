<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_loads(): void
    {
        $this->get(route('register'))
            ->assertStatus(200)
            ->assertViewIs('auth.register');
    }

    public function test_user_can_register(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Ali Valiyev',
            'email'                 => 'ali@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'name'  => 'Ali Valiyev',
            'email' => 'ali@example.com',
        ]);
    }

    public function test_user_is_authenticated_after_register(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Ali Valiyev',
            'email'                 => 'ali@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
    }

    public function test_password_is_hashed(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password123', $user->password));
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'ali@example.com']);

        $this->post(route('register'), [
            'name'                  => 'Ali',
            'email'                 => 'ali@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_passwords_must_match(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Ali',
            'email'                 => 'ali@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'different',
        ])->assertSessionHasErrors('password');
    }
}
