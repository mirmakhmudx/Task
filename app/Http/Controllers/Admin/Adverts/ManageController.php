<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Advert;
use App\Http\Controllers\Controller;
use App\UseCases\Adverts\AdvertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ManageController extends Controller
{
    public function __construct(
        private readonly AdvertService $service
    ) {}

    public function moderate(Advert $advert): RedirectResponse
    {
        Gate::authorize('admin-panel');

        try {
            $this->service->moderate($advert->id);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('adverts.show', $advert)
            ->with('success', 'E\'lon tasdiqlandi.');
    }

    public function reject(Request $request, Advert $advert): RedirectResponse
    {
        Gate::authorize('admin-panel');

        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        try {
            $this->service->reject($advert->id, $request->reason);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('adverts.show', $advert)
            ->with('success', 'E\'lon rad etildi.');
    }
}
