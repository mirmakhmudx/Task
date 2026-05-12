<?php

namespace App\Models;

use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use InvalidArgumentException;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const STATUS_WAIT   = 'waiting';
    public const STATUS_ACTIVE = 'active';

    public const ROLE_USER      = 'user';
    public const ROLE_MODERATOR = 'moderator';
    public const ROLE_MANAGER   = 'manager';
    public const ROLE_ADMIN     = 'admin';

    protected $fillable = [
        'name', 'last_name', 'email', 'password',
        'status', 'verify_token', 'role',
        'phone', 'phone_verified', 'phone_verify_token',
        'phone_verify_token_expire', 'two_factor_auth',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'         => 'datetime',
            'phone_verify_token_expire' => 'datetime',
            'password'                  => 'hashed',
            'phone_verified'            => 'boolean',
            'two_factor_auth'           => 'boolean',
        ];
    }

    // ===== Register =====

    public static function register(string $name, string $email, string $password): self
    {
        return static::create([
            'name'         => $name,
            'email'        => $email,
            'password'     => bcrypt($password),
            'status'       => self::STATUS_WAIT,
            'role'         => self::ROLE_USER,
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

    // ===== Email Verify =====

    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('Foydalanuvchi allaqachon tasdiqlangan.');
        }
        $this->update([
            'status'       => self::STATUS_ACTIVE,
            'verify_token' => null,
        ]);
    }

    // ===== Status =====

    public function isWait(): bool   { return $this->status === self::STATUS_WAIT; }
    public function isActive(): bool { return $this->status === self::STATUS_ACTIVE; }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('User is already active.');
        }
        $this->update(['status' => self::STATUS_ACTIVE]);
    }
    protected static function newFactory()
 {
   return \Database\Factories\User\UserFactory::new();
 }


    // ===== Role =====

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

    public function isAdmin(): bool     { return $this->role === self::ROLE_ADMIN; }
    public function isModerator(): bool { return $this->role === self::ROLE_MODERATOR; }
    public function isManager(): bool   { return $this->role === self::ROLE_MANAGER; }
    public function isUser(): bool      { return $this->role === self::ROLE_USER; }

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

    // ===== Phone =====

    public function isPhoneVerified(): bool
    {
        return $this->phone_verified === true;
    }


    public function unverifyPhone(): void
    {
        $this->phone_verified            = false;
        $this->phone_verify_token        = null;
        $this->phone_verify_token_expire = null;
    }

    public function requestPhoneVerification(Carbon $now): string
    {
        if (empty($this->phone)) {
            throw new DomainException('Phone number is empty.');
        }

        $expire = $this->phone_verify_token_expire;
        if (is_string($expire)) {
            $expire = Carbon::parse($expire);
        }

        if (
            !empty($this->phone_verify_token) &&
            $expire &&
            $expire->gt($now)
        ) {
            throw new DomainException('Token is already requested.');
        }

        $this->phone_verified            = false;
        $this->phone_verify_token        = (string) random_int(10000, 99999);
        $this->phone_verify_token_expire = $now->copy()->addSeconds(300);

        return $this->phone_verify_token;
    }

    public function verifyPhone(string $token, Carbon $now): void
    {
        if ($token !== $this->phone_verify_token) {
            throw new DomainException('Incorrect verify token.');
        }

        $expire = $this->phone_verify_token_expire;
        if (is_string($expire)) {
            $expire = Carbon::parse($expire);
        }

        if ($expire->lt($now)) {
            throw new DomainException('Token is expired.');
        }

        $this->phone_verified            = true;
        $this->phone_verify_token        = null;
        $this->phone_verify_token_expire = null;
    }

    // ===== Two Factor Auth =====

    public function isTwoFactorEnabled(): bool
    {
        return $this->two_factor_auth === true;
    }

    public function enableTwoFactor(): void
    {
        if (!$this->isPhoneVerified()) {
            throw new DomainException('Phone must be verified to enable two factor auth.');
        }
        if ($this->isTwoFactorEnabled()) {
            throw new DomainException('Two factor auth is already enabled.');
        }
        $this->update(['two_factor_auth' => true]);
    }

    public function disableTwoFactor(): void
    {
        if (!$this->isTwoFactorEnabled()) {
            throw new DomainException('Two factor auth is already disabled.');
        }
        $this->update(['two_factor_auth' => false]);
    }

    public function hasFilledProfile(): bool
    {
        return !empty($this->name) &&
            !empty($this->last_name) &&
            $this->isPhoneVerified();
    }
}
