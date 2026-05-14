<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Photo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Adverts\AttributesRequest;
use App\Http\Requests\Cabinet\Adverts\PhotosRequest;
use App\UseCases\Adverts\AdvertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ManageController extends Controller
{
    public function __construct(private readonly AdvertService $service)
    {
    }

    public function editAttributes(Advert $advert)
    {
        $this->checkOwner($advert);
        return view('cabinet.adverts.edit.attributes', compact('advert'));
    }

    public function UpdateAttributes(AttributesRequest $request, Advert $advert): RedirectResponse
    {
        $this->checkOwner($advert);

        try {
            $this->service->editAttributes($request, $advert->id);
        } catch (\DomainException $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }

        return redirect()->route('cabinet.adverts.show', ['advert' => $advert])
            ->with('success', 'Xususiyatlar yangilandi.');
    }

    public function editPhotos(Advert $advert)
    {
        $this->checkOwner($advert);
        return view('cabinet.adverts.edit.photos', compact('advert'));
    }

    public function UpdatePhotos(PhotosRequest $request, Advert $advert): RedirectResponse
    {
        $this->checkOwner($advert);

        try {
            $this->service->addPhotos($advert->id, $request);
        } catch (\DomainException $exception) {
            return back()->withErrors([
                'error' => $exception->getMessage()
            ]);
        }

        return redirect()
            ->route('cabinet.adverts.photos.edit', $advert)
            ->with('success', 'Rasmlar yuklandi.');
    }

    public function destroyPhoto(Advert $advert, Photo $photo): RedirectResponse
    {
        $this->checkOwner($advert);

        try {
            $this->service->removePhoto($advert->id, $photo->id);
        } catch (\DomainException $exception) {
            return back()->withErrors([
                'error' => $exception->getMessage()
            ]);
        }

        return back()->with('success', 'Rasm o\'chirildi.');
    }


    public function sendToModeration(Advert $advert): RedirectResponse
    {
        $this->checkOwner($advert);

        try {
            $this->service->sendToModeration($advert->id);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.adverts.show', $advert)
            ->with('success', 'E\'lon moderatsiyaga yuborildi.');
    }

    public function destroy(Advert $advert): RedirectResponse
    {
        $this->checkOwner($advert);

        try {
            $this->service->remove($advert->id);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.adverts.index')
            ->with('success', 'E\'lon o\'chirildi.');
    }

    public function checkOwner(Advert $advert)
    {
        if($advert->user_id !== Auth::id())
        {
            abort(403);
        }
    }

}
