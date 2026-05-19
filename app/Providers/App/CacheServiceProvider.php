<?php

namespace App\Providers\App;

use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    private array $classes = [
        Region::class,
        Category::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        foreach ($this->classes as $class) {
            $this->registerFlusher($class);
        }
    }

    private function registerFlusher(string $class): void
    {
        $cacheKey = strtolower(class_basename($class));

        $class::saved(fn($model) => Cache::forget("{$cacheKey}_path_{$model->id}"));
        $class::deleted(fn($model) => Cache::forget("{$cacheKey}_path_{$model->id}"));
    }
}
