<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    public function run(): void
    {
        factory(\App\Entity\User::class, 10)->create();
    }
}
