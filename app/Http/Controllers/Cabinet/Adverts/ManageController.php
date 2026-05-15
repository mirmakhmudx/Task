<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Adverts\AttributesRequest;
use App\Http\Requests\Cabinet\Adverts\PhotosRequest;
use App\UseCases\Adverts\AdvertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ManageController extends Controller
{
    public function __construct(
        private readonly AdvertService $service
    ) {}

    // ==================== ATTRIBUTES ====================

    public function editAttributes(Advert $advert): View
    {
        if ($advert->user_id !== Auth::id()) {
            abort(403);
        }

        return view('cabinet.adverts.edit.attributes', compact('advert'));
    }

    public function UpdateAttributes(AttributesRequest $request, Advert $advert): RedirectResponse
    {
        if ($advert->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $this->service->editAttributes($advert->id, $request);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.adverts.show', $advert)
            ->with('success', 'Atributlar yangilandi.');
    }

    // ==================== PHOTOS ====================

    public function editPhotos(Advert $advert): View
    {
        if ($advert->user_id !== Auth::id()) {
            abort(403);
        }

        return view('cabinet.adverts.edit.photos', compact('advert'));
    }

    public function UpdatePhotos(PhotosRequest $request, Advert $advert): RedirectResponse
    {
        if ($advert->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $this->service->addPhotos($advert->id, $request);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.adverts.show', $advert)
            ->with('success', 'Rasmlar yuklandi.');
    }

    public function destroyPhoto(Advert $advert, $photo): RedirectResponse
    {
        if ($advert->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $this->service->removePhoto($advert->id, $photo);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Rasm o\'chirildi.');
    }

    // ==================== MODERATION ====================

    public function sendToModeration(Advert $advert): RedirectResponse
    {
        if ($advert->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $this->service->sendToModeration($advert->id);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.adverts.show', $advert)
            ->with('success', 'Moderatsiyaga yuborildi.');
    }

    // ==================== DELETE ====================

    public function destroy(Advert $advert): RedirectResponse
    {
        if ($advert->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $this->service->remove($advert->id);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.adverts.index')
            ->with('success', 'E\'lon o\'chirildi.');
    }
}
