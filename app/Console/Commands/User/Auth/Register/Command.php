<?php

namespace App\Console\Commands\User\Auth\Register;

class Command
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {}
}
