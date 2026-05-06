<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'admin@app.com',
            'password' => Hash::make('password'),
            'status'   => 'active',
        ]);

        // Test userlar
        User::factory()->count(10)->active()->create();
        User::factory()->count(5)->waiting()->create();
    }
}
