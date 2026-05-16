<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdvertController extends Controller
{
    public function index(): View
    {
        $adverts = Advert::where('user_id', Auth::id())
            ->with(['category', 'region'])
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('cabinet.adverts.index', compact('adverts'));
    }

    public function create(): View
    {
        $categories = Category::defaultOrder()->withDepth()->get();
        $regions    = Region::whereNull('parent_id')->orderBy('name')->get();

        return view('cabinet.adverts.create', compact('categories', 'regions'));
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $advert = Advert::create([
            'user_id'     => Auth::id(),
            'category_id' => $request->category_id,
            'region_id'   => $request->region_id ?: null,
            'title'       => $request->title,
            'content'     => $request->input('content', ''),
            'price'       => $request->price ?: null,
            'address'     => $request->address,
            'status'      => Advert::STATUS_DRAFT,
        ]);

        return redirect()->route('cabinet.adverts.show', $advert)
            ->with('success', 'E\'lon yaratildi.');
    }

    public function show(Advert $advert): View
    {
        if ($advert->user_id !== Auth::id()) {
            abort(403);
        }

        $advert->load(['category', 'region', 'values.attribute', 'photos', 'user']);

        $similar = Advert::where('category_id', $advert->category_id)
            ->where('status', Advert::STATUS_ACTIVE)
            ->where('id', '!=', $advert->id)
            ->with(['photos'])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('cabinet.adverts.show', compact('advert', 'similar'));
    }
}
