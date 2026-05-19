<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert;
use App\Http\Controllers\Controller;
use App\UseCases\Adverts\FavoriteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    private FavoriteService $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $favoriteIds = DB::table('advert_user_favorites')
            ->where('user_id', Auth::id())
            ->pluck('advert_id');

        $adverts = Advert::whereIn('id', $favoriteIds)
            ->with(['category', 'region'])
            ->paginate(20);

        return view('cabinet.adverts.favorites.index', compact('adverts'));
    }

    public function add(Advert $advert): RedirectResponse
    {
        try {
            $this->service->add(Auth::id(), $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert)
            ->with('success', 'Advert is added to your favorites.');
    }

    public function remove(Advert $advert): RedirectResponse
    {
        try {
            $this->service->remove(Auth::id(), $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert)
            ->with('success', 'Advert is removed from your favorites.');
    }
}
