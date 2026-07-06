<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert;
use App\Http\Controllers\Controller;
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
