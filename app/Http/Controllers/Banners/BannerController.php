<?php

namespace App\Http\Controllers\Banners;

use App\Entity\Banner\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    // Ajax: formatga mos bannerni tanlaydi, ko'rsatishni sanaydi, HTML qaytaradi
    public function get(Request $request): string
    {
        $format     = $request->get('format');
        $regionId   = $request->get('region');
        $categoryId = $request->get('category');

        $banner = Banner::active()
            ->where('format', $format)
            ->when($regionId, function ($q) use ($regionId) {
                $q->where(function ($w) use ($regionId) {
                    $w->whereNull('region_id')->orWhere('region_id', $regionId);
                });
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->inRandomOrder()
            ->first();

        if (!$banner) {
            return '';
        }

        $banner->view(); // ko'rsatish +1

        return view('banner.get', compact('banner'))->render();
    }

    // Bosilganda: clicks +1 va banner url'iga yo'naltirish
    public function click(Banner $banner): RedirectResponse
    {
        $banner->click();
        return redirect()->away($banner->url);
    }
}
