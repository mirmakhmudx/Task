<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Advert;
use App\Http\Controllers\Controller;
use App\UseCases\Adverts\AdvertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ManageController extends Controller
{
    public function __construct(
        private readonly AdvertService $service
    ) {}

    // Barcha e'lonlar ro'yxati (filter bilan)
    public function index(Request $request): View
    {
        Gate::authorize('admin-panel');

        $query = Advert::with(['user', 'region', 'category'])->orderByDesc('id');

        if ($id = $request->input('id')) {
            $query->where('id', $id);
        }

        if ($title = $request->input('title')) {
            $query->where('title', 'like', "%{$title}%");
        }

        if ($user = $request->input('user')) {
            if (ctype_digit((string) $user)) {
                $query->where('user_id', $user);
            } else {
                $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$user}%"));
            }
        }

        if ($region = $request->input('region')) {
            if (ctype_digit((string) $region)) {
                $query->where('region_id', $region);
            } else {
                $query->whereHas('region', fn ($q) => $q->where('name', 'like', "%{$region}%"));
            }
        }

        if ($category = $request->input('category')) {
            if (ctype_digit((string) $category)) {
                $query->where('category_id', $category);
            } else {
                $query->whereHas('category', fn ($q) => $q->where('name', 'like', "%{$category}%"));
            }
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $adverts  = $query->paginate(20)->withQueryString();
        $statuses = [
            Advert::STATUS_DRAFT      => 'Draft',
            Advert::STATUS_MODERATION => 'On Moderation',
            Advert::STATUS_ACTIVE     => 'Active',
            Advert::STATUS_CLOSED     => 'Closed',
        ];

        return view('admin.adverts.index', compact('adverts', 'statuses'));
    }

    public function show(Advert $advert): View
    {
        Gate::authorize('admin-panel');
        return view('admin.adverts.show', compact('advert'));
    }

    public function rejectForm(Advert $advert): View
    {
        Gate::authorize('admin-panel');
        return view('admin.adverts.reject', compact('advert'));
    }

    public function moderate(Advert $advert): RedirectResponse
    {
        Gate::authorize('admin-panel');

        try {
            $this->service->moderate($advert->id);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.adverts.show', $advert)
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

        return redirect()->route('admin.adverts.show', $advert)
            ->with('success', 'E\'lon rad etildi.');
    }

    public function destroy(Advert $advert): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $this->service->remove($advert->id);

        return redirect()->route('admin.adverts.index')
            ->with('success', 'E\'lon o\'chirildi.');
    }
}
