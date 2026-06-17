<?php

namespace App\Http\Controllers\Api;

use App\Entity\Adverts\Advert;
use App\Http\Controllers\Controller;
use App\Http\Resources\Adverts\AdvertListResource;
use App\UseCases\Adverts\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FavoriteController extends Controller
{
    public function __construct(
        private readonly FavoriteService $service
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $adverts = $request->user()->favorites()
            ->with(['category', 'region', 'photos'])
            ->paginate(20);

        return AdvertListResource::collection($adverts);
    }

    public function store(Request $request, Advert $advert): JsonResponse
    {
        try {
            $this->service->add($request->user()->id, $advert->id);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
        return response()->json(['message' => 'Added to favorites']);
    }

    public function destroy(Request $request, Advert $advert): JsonResponse
    {
        $this->service->remove($request->user()->id, $advert->id);
        return response()->json(['message' => 'Removed from favorites']);
    }
}
