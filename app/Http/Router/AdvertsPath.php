<?php

namespace App\Http\Router;

use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Facades\Cache;

class AdvertsPath implements UrlRoutable
{
    public ?Region $region;
    public ?Category $category;

    public function __construct(?Region $region = null, ?Category $category = null)
    {
        $this->region   = $region;
        $this->category = $category;
    }

    public function withRegion(?Region $region): static
    {
        $this->region = $region;
        return $this;
    }

    public function withCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getRouteKey(): string
    {
        $segments = [];

        if ($this->region) {
            $segments[] = Cache::rememberForever(
                "region_path_{$this->region->id}",
                fn() => $this->region->slug
            );
        }

        if ($this->category) {
            $segments[] = Cache::rememberForever(
                "category_path_{$this->category->id}",
                fn() => $this->category->slug
            );
        }
        return implode('/', array_filter($segments));
    }

    public function getRouteKeyName(): string
    {
        return 'adverts_path';
    }

    public function resolveRouteBinding($value, $field = null): ?static
    {
        return null;
    }

    public function resolveChildRouteBinding($childType, $value, $field): ?static
    {
        return null;
    }
}
