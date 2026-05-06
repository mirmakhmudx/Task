<?php

namespace App\Console\Commands\User;

use App\Entity\User;
use App\Services\Auth\RegisterService; // Service namespace to'g'riligini tekshiring
use Illuminate\Console\Command;

class VerifyCommand extends Command
{
    protected $signature = 'user:verify {email}';
    protected $description = 'Foydalanuvchini email orqali tasdiqlash';

    // 1. BU YERDA PROPERTY E'LON QILINISHI SHART
    private $service;

    // 2. KONSTRUKTORDA SERVICE'NI BOG'LAYMIZ
    public function __construct(RegisterService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle(): bool
    {
        $email = $this->argument('email');

        if (!$user = User::where('email', $email)->first()) {
            $this->error("Foydalanuvchi topilmadi: " . $email);
            return false;
        }

        try {
            // ENDI $this->service ISHLAYDI
            $this->service->verify($user->id);
            $this->info("Muvaffaqiyatli: '{$email}' tasdiqlandi!");
        } catch (\DomainException $e) {
            $this->error($e->getMessage());
            return false;
        }

        return true;
    }
}
