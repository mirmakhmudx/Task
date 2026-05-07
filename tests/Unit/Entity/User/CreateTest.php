<?php

namespace Tests\Unit\Entity\User;

use App\Models\User;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_waiting_user(): void
    {
        $user = User::register('Ali', 'ali@test.com', 'password123');

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());
        self::assertEquals(User::ROLE_USER, $user->role);
    }

    public function test_new_creates_active_user(): void
    {
        $user = User::new('Ali', 'ali@test.com');

        self::assertTrue($user->isActive());
        self::assertEquals(User::ROLE_USER, $user->role);
    }

    public function test_activate(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_WAIT]);

        $user->activate();

        self::assertTrue($user->isActive());
    }

    public function test_activate_already_active(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('User is already active.');

        $user->activate();
    }
}
