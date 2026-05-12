<?php

namespace Tests\Unit\Entity\User;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class PhoneTest extends TestCase
{
    private function makeUser(array $attrs): User
    {
        $user = new User();
        foreach ($attrs as $key => $value) {
            $user->$key = $value;
        }
        return $user;
    }

    public function test_default_phone_is_not_verified(): void
    {
        $user = $this->makeUser([
            'phone'              => null,
            'phone_verified'     => false,
            'phone_verify_token' => null,
        ]);

        self::assertFalse($user->isPhoneVerified());
    }

    public function test_request_fails_if_phone_is_empty(): void
    {
        $user = $this->makeUser([
            'phone'              => null,
            'phone_verified'     => false,
            'phone_verify_token' => null,
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Phone number is empty.');

        $user->requestPhoneVerification(Carbon::now());
    }

    public function test_request_fails_if_token_already_requested(): void
    {
        $now  = Carbon::now();
        $user = $this->makeUser([
            'phone'                     => '+998901234567',
            'phone_verified'            => false,
            'phone_verify_token'        => '12345',
            'phone_verify_token_expire' => $now->copy()->addSeconds(200),
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Token is already requested.');

        $user->requestPhoneVerification($now);
    }

    public function test_request_succeeds_if_token_expired(): void
    {
        $now  = Carbon::now();
        $user = $this->makeUser([
            'phone'                     => '+998901234567',
            'phone_verified'            => false,
            'phone_verify_token'        => '12345',
            'phone_verify_token_expire' => $now->copy()->subSeconds(10),
        ]);

        $token = $user->requestPhoneVerification($now);

        self::assertNotEmpty($token);
        self::assertEquals(5, strlen($token));
        self::assertFalse($user->phone_verified);
    }

    public function test_verify_fails_with_wrong_token(): void
    {
        $now  = Carbon::now();
        $user = $this->makeUser([
            'phone'                     => '+998901234567',
            'phone_verified'            => false,
            'phone_verify_token'        => '12345',
            'phone_verify_token_expire' => $now->copy()->addSeconds(300),
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Incorrect verify token.');

        $user->verifyPhone('99999', $now);
    }

    public function test_verify_fails_with_expired_token(): void
    {
        $now  = Carbon::now();
        $user = $this->makeUser([
            'phone'                     => '+998901234567',
            'phone_verified'            => false,
            'phone_verify_token'        => '12345',
            'phone_verify_token_expire' => $now->copy()->subSeconds(1),
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Token is expired.');

        $user->verifyPhone('12345', $now);
    }

    public function test_verify_succeeds_with_correct_token(): void
    {
        $now  = Carbon::now();
        $user = $this->makeUser([
            'phone'                     => '+998901234567',
            'phone_verified'            => false,
            'phone_verify_token'        => '12345',
            'phone_verify_token_expire' => $now->copy()->addSeconds(300),
        ]);

        $user->verifyPhone('12345', $now);

        self::assertTrue($user->phone_verified);
        self::assertNull($user->phone_verify_token);
        self::assertNull($user->phone_verify_token_expire);
    }
}
