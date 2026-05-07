<?php

namespace Tests\Unit\Entity\User;

use App\Models\User;
use DomainException;
use InvalidArgumentException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_change_role(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        self::assertFalse($user->isAdmin());

        $user->changeRole(User::ROLE_ADMIN);

        self::assertTrue($user->isAdmin());
    }

    public function test_already_assigned_role(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Role is already assigned.');

        $user->changeRole(User::ROLE_ADMIN);
    }

    public function test_undefined_role(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->expectException(InvalidArgumentException::class);

        $user->changeRole('super-admin');
    }

    public function test_moderator_cannot_manage_users(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_MODERATOR]);

        self::assertTrue($user->isModerator());
        self::assertTrue($user->canAccessAdminPanel());
        self::assertFalse($user->canManageUsers());
    }

    public function test_only_admin_can_manage_users(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $mod   = User::factory()->create(['role' => User::ROLE_MODERATOR]);
        $plain = User::factory()->create(['role' => User::ROLE_USER]);

        self::assertTrue($admin->canManageUsers());
        self::assertFalse($mod->canManageUsers());
        self::assertFalse($plain->canManageUsers());
    }
}
