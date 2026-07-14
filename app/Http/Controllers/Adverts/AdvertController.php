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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        // ── Sub-regionlar + soni ─────────────────────────────────────────────
        $regionIds = $region
            ? [$region->id]
            : Region::roots()->pluck('id')->toArray();

        // Har bir region uchun aktiv e'lonlar soni
        $regionCounts = Advert::active()
            ->select('region_id', DB::raw('count(*) as adverts_count'))
            ->whereIn('region_id', $this->getAllRegionIds($region))
            ->groupBy('region_id')
            ->pluck('adverts_count', 'region_id')
            ->toArray();

        $regions = $region
            ? $region->children()->orderBy('name')->get()
            : Region::roots()->orderBy('name')->get();

        $regions->each(function ($r) use ($regionCounts) {
            $r->adverts_count = $regionCounts[$r->id] ?? 0;
        });

        $categoryList = $category
            ? $category->children()->orderBy('name')->get()
            : Category::roots()->orderBy('name')->get();

        $categoryCounts = Advert::active()
            ->select('category_id', DB::raw('count(*) as adverts_count'))
            ->groupBy('category_id')
            ->pluck('adverts_count', 'category_id')
            ->toArray();

        $categoryList->each(function ($cat) use ($categoryCounts) {
            $cat->adverts_count = $categoryCounts[$cat->id] ?? 0;
        });

        // ── Autocomplete AJAX ────────────────────────────────────────────────
        if ($request->ajax() || $request->has('autocomplete')) {
            return $this->autocompleteResponse($adverts, $request);
        }

        return view('adverts.index', compact(
            'category', 'region', 'categoryList', 'regions', 'adverts', 'path'
        ));
    }

    private function getAllRegionIds(?Region $region): array
    {
        if (!$region) {
            return Region::pluck('id')->toArray();
        }
        $ids = [$region->id];
        foreach ($region->children as $child) {
            $ids = array_merge($ids, $this->getAllRegionIds($child));
        }
        return $ids;
    }

    private function autocompleteResponse($adverts, SearchRequest $request): JsonResponse
    {
        $items = [];

        foreach ($adverts->take(6) as $advert) {
            $photo   = $advert->photos->first();
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
