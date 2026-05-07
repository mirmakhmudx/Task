<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        ];
    }

    public function waiting(): static
    {
        return $this->state(['status' => User::STATUS_WAIT]);
    }

    public function active(): static
    {
        return $this->state(['status' => User::STATUS_ACTIVE]);
    }

    public function admin(): static
    {
        return $this->state(['role' => User::ROLE_ADMIN]);
    }

    public function moderator(): static
    {
        return $this->state(['role' => User::ROLE_MODERATOR]);
    }
    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }
}
