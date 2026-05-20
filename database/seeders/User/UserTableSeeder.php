<?php

namespace Database\Seeders\User;

use App\Models\User;
use Illuminate\Database\Seeder;
use function Database\Seeders\factory;

class UserTableSeeder extends Seeder
{

    public function run(): void
    {
        User::factory()->count(10)->create();    }
}
