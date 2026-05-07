<?php

namespace Database\Seeders;

use Database\Seeders\Region\RegionsTableSeeder;
use Database\Seeders\User\UserTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
    }
}
