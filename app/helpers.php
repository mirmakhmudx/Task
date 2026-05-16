<?php

use App\Http\Router\AdvertsPath;
use App\Entity\Region\Region;
use App\Entity\Adverts\Category;

if (! function_exists('adverts_path')) {
    function adverts_path(?Region $region = null, ?Category $category = null): AdvertsPath
    {
        return app()->make(AdvertsPath::class)
            ->withRegion($region)
            ->withCategory($category);
    }
}
