<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
#[Fillable(['name', 'email', 'password','status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{

    public const STATUS_WAIT = 'waiting';
     public const STATUS_ACTIVE = 'active';
    use HasFactory, Notifiable;

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



}
