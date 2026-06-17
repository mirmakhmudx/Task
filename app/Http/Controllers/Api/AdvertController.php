<?php

namespace App\Http\Controllers\Api;

use App\Entity\Adverts\Advert;
use App\Http\Controllers\Controller;
use App\Http\Resources\Adverts\AdvertDetailResource;
use App\Http\Resources\Adverts\AdvertListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdvertController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $adverts = Advert::active()
            ->with(['category', 'region', 'photos'])
            ->orderByDesc('published_at')
            ->paginate(20);

        return AdvertListResource::collection($adverts);
    }

    public function show(Advert $advert): AdvertDetailResource
    {
        abort_if(!$advert->isActive(), 404);
        $advert->load(['category', 'region', 'photos']);
        return new AdvertDetailResource($advert);
    }
}
