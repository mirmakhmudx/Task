<?php

namespace App\Http\Controllers\Cabinet\Banners;

use App\Entity\Banner\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::forUser(Auth::user())
            ->with(['category', 'region'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('cabinet.banners.index', compact('banners'));
    }

    public function show(Banner $banner): View
    {
        $this->checkAccess($banner);
        return view('cabinet.banners.show', compact('banner'));
    }

    private function checkAccess(Banner $banner): void
    {
        if (!$banner->isOwnedBy(Auth::user())) {
            abort(403);
        }
    }
}
