<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use App\Http\Controllers\Controller;
use App\Http\Router\AdvertsPath;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertController extends Controller
{
    public function index(Request $request, AdvertsPath $path): View
    {
        $query = Advert::active()
            ->with(['category', 'region', 'photos'])
            ->orderByDesc('published_at');

        if ($path->region) {
            $query->forRegion($path->region);
        }

        if ($path->category) {
            $query->forCategory($path->category);
        }

        $adverts = $query->paginate(20);

        $regions = Region::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();

        return view('adverts.index', compact('adverts', 'path', 'regions', 'categories'));
    }
}
