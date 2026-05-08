<?php

namespace Database\Seeders\Region;

use App\Entity\Region\Region;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    public function run(): void
    {
        // 10 ta viloyat yaratamiz
        Region::factory()
            ->count(10)
            ->create()
            ->each(function (Region $region): void {

                // Har bir viloyatga 3-10 tuman
                $districts = Region::factory()
                    ->count(random_int(3, 10))
                    ->withParent($region->id)
                    ->create();

                // Har bir tumanga 3-10 mahalla
                $districts->each(function (Region $district): void {
                    Region::factory()
                        ->count(random_int(3, 10))
                        ->withParent($district->id)
                        ->create();
                });
            });
    }
}
