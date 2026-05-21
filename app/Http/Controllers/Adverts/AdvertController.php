<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Router\AdvertsPath;
use App\UseCases\Adverts\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdvertController extends Controller
{
    private $search;

    public function __construct(SearchService $search)
    {
        $this->search = $search;
    }

    public function index(SearchRequest $request, AdvertsPath $path)
    {
        $category = $path->category;
        $region   = $path->region;

        $page    = $request->get('page', 1);
        $perPage = 20;

        $adverts = $this->search->search($category, $region, $request, $perPage, $page);

        $regions = $region
            ? $region->children()->orderBy('name')->getModels()
            : Region::roots()->orderBy('name')->getModels();

        $categories = $category
            ? $category->children()->orderBy('name')->getModels()
            : Category::roots()->orderBy('name')->getModels();

        // Autocomplete AJAX so'rovi
        if ($request->ajax() || $request->has('autocomplete')) {
            return $this->autocompleteResponse($adverts, $request);
        }

        return view('adverts.index', compact(
            'category', 'region', 'categories', 'regions', 'adverts', 'path'
        ));
    }

    private function autocompleteResponse($adverts, SearchRequest $request): JsonResponse
    {
        $items = [];

        foreach ($adverts->take(6) as $advert) {
            $photo = $advert->photos->first();

            $items[] = [
                'title'    => $advert->title,
                'url'      => route('adverts.show', $advert),
                'photo'    => $photo ? Storage::url($photo->file) : null,
                'price'    => $advert->price
                    ? number_format($advert->price, 0, '.', ' ') . ' UZS'
                    : null,
                'region'   => $advert->region?->name,
                'category' => $advert->category?->name,
            ];
        }

        return response()->json([
            'items' => $items,
            'total' => $adverts->total(),
        ]);
    }
}
