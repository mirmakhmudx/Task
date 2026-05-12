<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_user_redirected_to_phone_form(): void
    {
        $user = User::factory()->create([
            'status'          => User::STATUS_ACTIVE,
            'phone'           => '+998901234567',
            'phone_verified'  => true,
            'two_factor_auth' => true,
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('login.phone'));
        $this->assertGuest();
    }

    public function test_phone_form_is_displayed(): void
    {
        $response = $this->get(route('login.phone'));

        $response->assertStatus(200);
    }

    public function test_verify_fails_with_wrong_token(): void
    {
        $user = User::factory()->create([
            'status'          => User::STATUS_ACTIVE,
            'phone'           => '+998901234567',
            'phone_verified'  => true,
            'two_factor_auth' => true,
        ]);

        $this->withSession([
            'auth' => [
                'id'       => $user->id,
                'token'    => '12345',
                'remember' => false,
            ],
        ]);

        $response = $this->post(route('login.phone.verify'), [
            'token' => '99999',
        ]);

        $response->assertSessionHasErrors('token');
        $this->assertGuest();
    }

    public function test_verify_succeeds_with_correct_token(): void
    {
        $user = User::factory()->create([
            'status'          => User::STATUS_ACTIVE,
            'phone'           => '+998901234567',
            'phone_verified'  => true,
            'two_factor_auth' => true,
        ]);

        $response = $this->withSession([
            'auth' => [
                'id'       => $user->id,
                'token'    => '12345',
                'remember' => false,
            ],
        ])->post(route('login.phone.verify'), [
            'token' => '12345',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_normal_user_not_redirected_to_phone(): void
    {
        $user = User::factory()->create([
            'status'          => User::STATUS_ACTIVE,
            'two_factor_auth' => false,
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
