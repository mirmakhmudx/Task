<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdvertShowController extends Controller
{
    public function show(Advert $advert): View
    {
        // Owner bo'lmagan va active bo'lmagan e'lonni ko'rsatma
        if (!$advert->isActive() && Auth::id() !== $advert->user_id) {
            abort(404);
        }

        $advert->load(['category', 'region', 'values.attribute', 'photos', 'user']);

        // O'xshash e'lonlar
        $similar = Advert::where('category_id', $advert->category_id)
            ->where('status', Advert::STATUS_ACTIVE)
            ->where('id', '!=', $advert->id)
            ->with(['photos'])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('adverts.show', compact('advert', 'similar'));
    }
}
