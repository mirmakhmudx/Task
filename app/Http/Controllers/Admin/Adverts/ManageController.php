<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Advert;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Adverts\PhotosRequest;
use App\Http\Requests\Cabinet\Adverts\AttributesRequest;
use App\UseCases\Adverts\AdvertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManageController extends Controller
{
    public function __construct(private readonly AdvertService $service) {}

    public function show(Advert $advert): View
    {
        return view('admin.adverts.show', compact('advert'));
    }

    public function moderate(Advert $advert): View
    {
        try{
            $this->service->moderate($advert->id);
        }catch (\DomainException $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
        return redirect()->route('admin.adverts.show', $advert)
            ->with('success', 'E\'lon tasdiqlandi.');
    }

    public function reject(Request $request, Advert $advert): RedirectResponse
    {
        $request->validate(['reason' => 'required|string']);

        try{
            $this->service->reject($advert->id, $request->reason);
        }catch (\DomainException $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function destroy(Advert $advert): RedirectResponse
    {
        try{
            $this->service->remove($advert->id);
        }catch (\DomainException $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }

        return redirect()->route('cabinet.adverts.index', $advert)
            ->with('success', 'E\'lon o\'chirildi.');
    }
}
