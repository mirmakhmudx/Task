<?php

namespace Tests\Unit\Entity;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // ==================== STATUS ====================

    public function test_new_user_is_waiting(): void
    {
        $user = User::factory()->wait()->create();

        $this->assertTrue($user->isWait());
        $this->assertFalse($user->isActive());
    }

    public function test_verify_activates_waiting_user(): void
    {
        $user = User::factory()->wait()->create([
            'verify_token' => 'some-token',
        ]);

        $user->verify();

        $this->assertTrue($user->isActive());
        $this->assertNull($user->verify_token);
    }

    public function test_verify_throws_if_already_active(): void
    {
        $user = User::factory()->active()->create();

        $this->expectException(\DomainException::class);

        $user->verify();
    }

    public function test_activate_throws_if_already_active(): void
    {
        $user = User::factory()->active()->create();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('User is already active.');

        $user->activate();
    }

    // ==================== ROLES ====================

    public function test_change_role_to_admin(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $user->changeRole(User::ROLE_ADMIN);

        $this->assertTrue($user->isAdmin());
    }

    public function test_change_role_throws_if_same_role(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Role is already assigned.');

        $user->changeRole(User::ROLE_USER);
    }

    public function test_change_role_throws_if_invalid_role(): void
    {
        $user = User::factory()->create();

        $this->expectException(\InvalidArgumentException::class);

        $user->changeRole('superuser');
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $user = User::factory()->admin()->create();
        $this->assertTrue($user->canAccessAdminPanel());
    }

    public function test_moderator_can_access_admin_panel(): void
    {
        $user = User::factory()->moderator()->create();
        $this->assertTrue($user->canAccessAdminPanel());
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);
        $this->assertFalse($user->canAccessAdminPanel());
    }

    // ==================== PHONE ====================

    public function test_request_phone_verification_generates_token(): void
    {
        $user = User::factory()->create([
            'phone'                    => '+998901234567',
            'phone_verify_token'       => null,
            'phone_verify_token_expire'=> null,
        ]);

        $token = $user->requestPhoneVerification(Carbon::now());

        $this->assertNotNull($token);
        $this->assertEquals(5, strlen($token));
    }

    public function test_request_phone_verification_throws_if_no_phone(): void
    {
        $user = User::factory()->create(['phone' => null]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Phone number is empty.');

        $user->requestPhoneVerification(Carbon::now());
    }

    public function test_verify_phone_with_correct_token(): void
    {
        $now  = Carbon::now();
        $user = User::factory()->create([
            'phone'                     => '+998901234567',
            'phone_verified'            => false,
            'phone_verify_token'        => '12345',
            'phone_verify_token_expire' => $now->copy()->addMinutes(5),
        ]);

        $user->verifyPhone('12345', $now);

        $this->assertTrue($user->isPhoneVerified());
        $this->assertNull($user->phone_verify_token);
    }

    public function test_verify_phone_throws_with_wrong_token(): void
    {
        $now  = Carbon::now();
        $user = User::factory()->create([
            'phone'                     => '+998901234567',
            'phone_verified'            => false,
            'phone_verify_token'        => '12345',
            'phone_verify_token_expire' => $now->copy()->addMinutes(5),
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Incorrect verify token.');

        $user->verifyPhone('99999', $now);
    }

    public function test_verify_phone_throws_if_token_expired(): void
    {
        $now  = Carbon::now();
        $user = User::factory()->create([
            'phone'                     => '+998901234567',
            'phone_verified'            => false,
            'phone_verify_token'        => '12345',
            'phone_verify_token_expire' => $now->copy()->subMinutes(10),
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Token is expired.');

        $user->verifyPhone('12345', $now);
    }

    // ==================== TWO FACTOR ====================

    public function test_enable_two_factor_requires_verified_phone(): void
    {
        $user = User::factory()->create([
            'phone_verified' => false,
            'two_factor_auth' => false,
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Phone must be verified to enable two factor auth.');

        $user->enableTwoFactor();
    }

    public function test_enable_two_factor_succeeds(): void
    {
        $user = User::factory()->create([
            'phone_verified'  => true,
            'two_factor_auth' => false,
        ]);

        $user->enableTwoFactor();

        $this->assertTrue($user->isTwoFactorEnabled());
    }

    public function test_disable_two_factor(): void
    {
        $user = User::factory()->create([
            'phone_verified'  => true,
            'two_factor_auth' => true,
        ]);

        $user->disableTwoFactor();

        $this->assertFalse($user->isTwoFactorEnabled());
    }

    // ==================== FILLED PROFILE ====================

    public function test_has_filled_profile_returns_true_when_complete(): void
    {
        $user = User::factory()->create([
            'name'           => 'Ali',
            'last_name'      => 'Valiyev',
            'phone_verified' => true,
        ]);

        $this->assertTrue($user->hasFilledProfile());
    }

    public function test_has_filled_profile_returns_false_without_last_name(): void
    {
        $user = User::factory()->create([
            'name'           => 'Ali',
            'last_name'      => null,
            'phone_verified' => true,
        ]);

        $this->assertFalse($user->hasFilledProfile());
    }

    public function test_has_filled_profile_returns_false_without_verified_phone(): void
    {
        $user = User::factory()->create([
            'name'           => 'Ali',
            'last_name'      => 'Valiyev',
            'phone_verified' => false,
        ]);

        $this->assertFalse($user->hasFilledProfile());
    }
}
