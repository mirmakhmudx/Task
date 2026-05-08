<?php

namespace Database\Factories\Region;

use App\Entity\Region\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RegionFactory extends Factory
{
    protected $model = Region::class;

    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'name'      => $name,
            'slug'      => Str::slug($name),
            'parent_id' => null,
        ];
    }

    public function withParent(int $parentId): static
    {
        return $this->state(['parent_id' => $parentId]);
    }
}
