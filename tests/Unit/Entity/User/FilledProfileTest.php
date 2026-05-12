<?php

namespace Tests\Unit\Entity\User;

use App\Models\User;
use Tests\TestCase;

class FilledProfileTest extends TestCase
{
    private function makeUser(array $attrs): User
    {
        $user = new User();
        foreach ($attrs as $key => $value) {
            $user->$key = $value;
        }
        return $user;
    }

    public function test_profile_is_not_filled_without_last_name(): void
    {
        $user = $this->makeUser([
            'name'           => 'John',
            'last_name'      => null,
            'phone_verified' => true,
        ]);

        self::assertFalse($user->hasFilledProfile());
    }

    public function test_profile_is_not_filled_without_phone_verified(): void
    {
        $user = $this->makeUser([
            'name'           => 'John',
            'last_name'      => 'Doe',
            'phone_verified' => false,
        ]);

        self::assertFalse($user->hasFilledProfile());
    }

    public function test_profile_is_not_filled_without_name(): void
    {
        $user = $this->makeUser([
            'name'           => null,
            'last_name'      => 'Doe',
            'phone_verified' => true,
        ]);

        self::assertFalse($user->hasFilledProfile());
    }

    public function test_profile_is_filled_with_all_fields(): void
    {
        $user = $this->makeUser([
            'name'           => 'John',
            'last_name'      => 'Doe',
            'phone_verified' => true,
        ]);

        self::assertTrue($user->hasFilledProfile());
    }
}
