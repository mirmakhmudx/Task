<?php

namespace Tests\Unit\Entity\User;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class TwoFactorTest extends TestCase
{
    public function test_two_factor_is_disabled_by_default(): void
    {
        $user = new User(['two_factor_auth' => false]);

        self::assertFalse($user->isTwoFactorEnabled());
    }

    public function test_enable_fails_if_phone_not_verified(): void
    {
        $user = new User([
            'phone_verified'  => false,
            'two_factor_auth' => false,
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Phone must be verified');

        $user->enableTwoFactor();
    }

    public function test_enable_fails_if_already_enabled(): void
    {
        $user = new User([
            'phone_verified'  => true,
            'two_factor_auth' => true,
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('already enabled');

        $user->enableTwoFactor();
    }

    public function test_disable_fails_if_already_disabled(): void
    {
        $user = new User([
            'two_factor_auth' => false,
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('already disabled');

        $user->disableTwoFactor();
    }
}
