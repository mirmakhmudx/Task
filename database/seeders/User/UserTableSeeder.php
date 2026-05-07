<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;
use function Database\Seeders\factory;

class UserTableSeeder extends Seeder
{

    public function run(): void
    {
        factory(\App\Entity\User\User::class, 10)->create();
    }
}
