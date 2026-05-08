<?php

namespace Database\Seeders;

use App\Entity\Adverts\Category;
use Illuminate\Database\Seeder;

class AdvertCategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        Category::factory()
            ->count(10)
            ->create()
            ->each(function (Category $category): void {
                $counts = [0, random_int(3, 7)];

                $category->children()->saveMany(
                    Category::factory()
                        ->count($counts[array_rand($counts)])
                        ->make()
                )->each(function (Category $category): void {
                    $counts = [0, random_int(3, 7)];

                    $category->children()->saveMany(
                        Category::factory()
                            ->count($counts[array_rand($counts)])
                            ->make()
                    );
                });
            });
    }
}
