<?php

namespace App\Console\Commands\User;

use App\Entity\User;
use Illuminate\Console\Command;

class VerifyCommand extends Command
{

    protected $signature = 'user:verify {email}';


    protected $description = 'Foydalanuvchining emailini terminal orqali tasdiqlash';


    public function handle(): void
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Xatolik: '{$email}' manzilli foydalanuvchi topilmadi.");
            return;
        }

        try {
            $user->verify();
            $this->info("Muvaffaqiyatli: '{$email}' foydalanuvchisi tasdiqlandi!");
        } catch (\DomainException $e) {
            $this->error($e->getMessage());
        }
    }
}
