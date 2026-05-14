<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\UseCases\Adverts\AdvertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CreateController extends Controller
{
    public function __construct(
        private readonly AdvertService $service
    ) {}

    public function category(): View
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();

        return view('cabinet.adverts.create.category', compact('categories'));
    }

    public function region(Category $category, Region $region = null): View
    {
        $regions = Region::where('parent_id', $region ? $region->id : null)
            ->orderBy('name')
            ->get();

        return view('cabinet.adverts.create.region',
            compact('category', 'region', 'regions')
        );
    }

    public function advert(Category $category, Region $region = null): View
    {
        return view('cabinet.adverts.create.advert',
            compact('category', 'region')
        );
    }


    public function store(CreateRequest $request, Category $category, Region $region = null): RedirectResponse
    {
        try {
            $advert = $this->service->create(
                Auth::id(),
                $category->id,
                $region?->id,
                $request
            );
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.adverts.index')
            ->with('success', 'E\'lon yaratildi.');
    }
}
