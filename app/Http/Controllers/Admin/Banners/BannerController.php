<?php

namespace App\Http\Controllers\Admin\Banners;

use App\Entity\Banner\Banner;
use App\Http\Controllers\Controller;
use App\UseCases\Banners\BannerService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function __construct(private readonly BannerService $service) {}

    public function index(Request $request): View
    {
        Gate::authorize('admin-panel');
        $query = Banner::with(['user', 'category', 'region'])->orderByDesc('id');
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%{$name}%");
        }
        $banners  = $query->paginate(20)->withQueryString();
        $statuses = [
            Banner::STATUS_DRAFT      => 'Draft',
            Banner::STATUS_MODERATION => 'Moderation',
            Banner::STATUS_WAIT_PAY   => 'Wait Pay',
            Banner::STATUS_ACTIVE     => 'Active',
            Banner::STATUS_CLOSED     => 'Closed',
        ];
        return view('admin.banners.index', compact('banners', 'statuses'));
    }

    public function show(Banner $banner): View
    {
        Gate::authorize('admin-panel');
        return view('admin.banners.show', compact('banner'));
    }

    public function moderate(Banner $banner): RedirectResponse
    {
        Gate::authorize('admin-panel');
        try {
            $this->service->moderate($banner->id);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
        return redirect()->route('admin.banners.show', $banner)->with('success', 'Banner tasdiqlandi — to\'lov kutilmoqda.');
    }

    public function pay(Banner $banner): RedirectResponse
    {
        Gate::authorize('admin-panel');
        try {
            $this->service->pay($banner->id);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
        return redirect()->route('admin.banners.show', $banner)->with('success', 'Banner aktiv qilindi.');
    }

    public function rejectForm(Banner $banner): View
    {
        Gate::authorize('admin-panel');
        return view('admin.banners.reject', compact('banner'));
    }

    public function reject(Request $request, Banner $banner): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $request->validate(['reason' => ['required', 'string', 'max:500']]);
        try {
            $this->service->reject($banner->id, $request->reason);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
        return redirect()->route('admin.banners.show', $banner)->with('success', 'Banner rad etildi.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $this->service->remove($banner->id);
        return redirect()->route('admin.banners.index')->with('success', 'Banner o\'chirildi.');
    }
}
