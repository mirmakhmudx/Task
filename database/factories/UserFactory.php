<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'password'          => Hash::make('password'),
            'status'            => fake()->randomElement(['active', 'waiting']),
            'email_verified_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(['status' => 'active']);
    }

    public function waiting(): static
    {
        return $this->state(['status' => 'waiting']);
    }

    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }
}
