<?php

namespace App\Entity;

use App\Models\User as UserModel;

class User extends UserModel
{
    // Biznes qoidalar shu yerda bo'ladi
    // Model "faqat DB" — Entity "biznes mantiq"

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
