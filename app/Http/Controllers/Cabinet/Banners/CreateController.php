<?php

namespace App\Http\Controllers\Cabinet\Banners;

use App\Entity\Adverts\Category;
use App\Entity\Banner\Banner;
use App\Entity\Region\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Banners\CreateRequest;
use App\UseCases\Banners\BannerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CreateController extends Controller
{
    public function __construct(
        private readonly BannerService $service
    ) {}

    public function category(): View
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();

        return view('cabinet.banners.create.category', compact('categories'));
    }

    public function region(Category $category, Region $region = null): View
    {
        $regions = Region::where('parent_id', $region ? $region->id : null)
            ->orderBy('name')
            ->get();

        return view('cabinet.banners.create.region',
            compact('category', 'region', 'regions')
        );
    }

    public function banner(Category $category, Region $region = null): View
    {
        $formats = Banner::formatsList();

        return view('cabinet.banners.create.banner',
            compact('category', 'region', 'formats')
        );
    }

    public function store(CreateRequest $request, Category $category, Region $region = null): RedirectResponse
    {
        try {
            $banner = $this->service->create(
                Auth::id(),
                $category->id,
                $region?->id,
                $request
            );
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.banners.show', $banner)
            ->with('success', 'Banner yaratildi.');
    }
}
