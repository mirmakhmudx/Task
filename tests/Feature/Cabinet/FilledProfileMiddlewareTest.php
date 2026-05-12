<?php

namespace Tests\Feature\Cabinet;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilledProfileMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_filled_profile_redirected(): void
    {
        $user = User::factory()->create([
            'last_name'      => null,
            'phone_verified' => false,
        ]);

        $response = $this->actingAs($user)->get(route('cabinet.adverts.index'));

        $response->assertRedirect(route('cabinet.profile.show'));
    }

    public function test_user_with_filled_profile_can_access_adverts(): void
    {
        $user = User::factory()->create([
            'name'           => 'John',
            'last_name'      => 'Doe',
            'phone'          => '+998901234567',
            'phone_verified' => true,
            'status'         => User::STATUS_ACTIVE,
        ]);

        $response = $this->actingAs($user)->get(route('cabinet.adverts.index'));

        $response->assertStatus(200);
    }
}
