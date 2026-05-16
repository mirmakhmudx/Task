<?php

namespace App\Http\Router;

use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use Illuminate\Contracts\Routing\UrlRoutable;

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
            $segments[] = $this->region->slug;
        }
        if ($this->category) {
            $segments[] = $this->category->slug;
        }
        return implode('/', $segments);
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
