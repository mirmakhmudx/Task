<?php

namespace Tests\Unit\Entity\User;

use App\Models\User;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PhoneTest extends TestCase
{
    public function test_default_phone_is_not_verified(): void
    {
        $user = new User([
            'phone'          => null,
            'phone_verified' => false,
            'phone_verify_token' => null,
        ]);

        self::assertFalse($user->isPhoneVerified());
    }

    public function test_request_fails_if_phone_is_empty(): void
    {
        $user = new User([
            'phone'          => null,
            'phone_verified' => false,
            'phone_verify_token' => null,
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Phone number is empty.');

        $user->requestPhoneVerification(Carbon::now());
    }

    public function test_request_fails_if_token_already_requested(): void
    {
        $user = new User([
            'phone'                      => '+998901234567',
            'phone_verified'             => false,
            'phone_verify_token'         => '12345',
            'phone_verify_token_expire'  => Carbon::now()->addSeconds(200),
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Token is already requested.');

        $user->requestPhoneVerification(Carbon::now());
    }

    public function test_verify_fails_with_wrong_token(): void
    {
        $user = new User([
            'phone'                     => '+998901234567',
            'phone_verified'            => false,
            'phone_verify_token'        => '12345',
            'phone_verify_token_expire' => Carbon::now()->addSeconds(300),
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Incorrect verify token.');

        $user->verifyPhone('99999', Carbon::now());
    }

    public function test_verify_fails_with_expired_token(): void
    {
        $user = new User([
            'phone'                     => '+998901234567',
            'phone_verified'            => false,
            'phone_verify_token'        => '12345',
            'phone_verify_token_expire' => Carbon::now()->subSeconds(1),
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Token is expired.');

        $user->verifyPhone('12345', Carbon::now());
    }
}
