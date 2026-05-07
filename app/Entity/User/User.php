<?php

namespace App\Entity\User;

use App\Models\User as UserModel;

class User extends UserModel
{


    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isWaiting(): bool
    {
        return $this->status === 'waiting';
    }

    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }
}
