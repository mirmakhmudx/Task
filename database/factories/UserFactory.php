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
            'name'              => fake()->firstName(),
            'last_name'         => fake()->lastName(),
            'email'             => fake()->unique()->safeEmail(),
            'password'          => 'password',
            'status'            => User::STATUS_ACTIVE,
            'role'              => User::ROLE_USER,
            'verify_token'      => null,
            'phone'             => null,
            'phone_verified'    => false,
            'phone_verify_token'        => null,
            'phone_verify_token_expire' => null,
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

    public function withPhone(string $phone = '+998901234567'): static
    {
        return $this->state([
            'phone'          => $phone,
            'phone_verified' => false,
        ]);
    }
}
