<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DomainException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use InvalidArgumentException;

#[Fillable(['name', 'last_name','email', 'password', 'status', 'verify_token', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const STATUS_WAIT   = 'waiting';
    public const STATUS_ACTIVE = 'active';

    public const ROLE_USER      = 'user';
    public const ROLE_MODERATOR = 'moderator';
    public const ROLE_MANAGER   = 'manager';
    public const ROLE_ADMIN     = 'admin';



    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('Foydalanuvchi allaqachon tasdiqlangan.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->verify_token = null;
    }

    public function isWait(): bool
    {
        return strtolower(trim($this->status)) === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('User is already active.');
        }

        $this->update(['status' => self::STATUS_ACTIVE]);
    }

    public static function rolesList(): array
    {
        return [
            self::ROLE_USER      => 'User',
            self::ROLE_MODERATOR => 'Moderator',
            self::ROLE_MANAGER   => 'Manager',
            self::ROLE_ADMIN     => 'Admin',
        ];
    }

    public function changeRole(string $role): void
    {
        if (!array_key_exists($role, self::rolesList())) {
            throw new InvalidArgumentException('Undefined role "' . $role . '"');
        }

        if ($this->role === $role) {
            throw new DomainException('Role is already assigned.');
        }

        $this->update(['role' => $role]);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isModerator(): bool
    {
        return $this->role === self::ROLE_MODERATOR;
    }

    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public static function register($name, $email, $password): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'status' => self::STATUS_WAIT,
            'role'     => self::ROLE_USER,
            'verify_token' => \Illuminate\Support\Str::uuid(),
        ]);
    }

    public static function new(string $name, string $email): self
    {
        return static::create([
            'name'     => $name,
            'email'    => $email,
            'role'     => self::ROLE_USER,
            'status'   => self::STATUS_ACTIVE,
            'password' => bcrypt(\Illuminate\Support\Str::random(16)),
        ]);
    }

    public function canAccessAdminPanel(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_MODERATOR,
            self::ROLE_MANAGER,
        ], true);
    }

    public function canManageUsers(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }



}
