<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'password'          => 'password',
            'status'            => User::STATUS_ACTIVE,
            'role'              => User::ROLE_USER,
            'verify_token'      => null,
            'email_verified_at' => now(),
            'remember_token'    => Str::random(10),
        ];
    }

    public function wait(): static
    {
        return $this->state([
            'status'            => User::STATUS_WAIT,
            'verify_token'      => Str::uuid(),
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state([
            'role'   => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);
    }
}
