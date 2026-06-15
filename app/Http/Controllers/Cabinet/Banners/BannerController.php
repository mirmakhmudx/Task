<?php

namespace App\Http\Controllers\Cabinet\Banners;

use App\Entity\Banner\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::ForUser(Auth::user())->with('category', 'region',)->orderBy('id')->paginate(15);
        return view('cabinet.banners.index', compact('banners'));

    }

    public function show(Banner $banner)
    {
        $this->chekAcces($banner);
        return view('cabinet.banners.show', compact('banner'));
    }

    public function chekAcces(Banner $banner)
    {
        if($banned->isOwnedBy(Auth::user())){
            abort(403);
        }
    }
}
