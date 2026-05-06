<?php

namespace App\Console\Handlers\User\Auth\Register;

use App\Console\Commands\User\Auth\Register\Command;
use App\Services\Auth\RegisterService;

class Handler
{
    public function __construct(
        private readonly RegisterService $registerService
    ) {}


    public function handle(Command $command)
    {
        return $this->registerService->handle([
            'name'     => $command->name,
            'email'    => $command->email,
            'password' => $command->password,
        ]);
    }
}
