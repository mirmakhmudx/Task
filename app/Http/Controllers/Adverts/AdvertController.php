<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Router\AdvertsPath;
use App\UseCases\Adverts\SearchService;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertController extends Controller
{

    private $search;

    public function __construct(SearchService $search)
    {
        $this->search = $search;
    }


    public function index(SearchRequest $request, \App\Http\Router\AdvertsPath $path)
    {
        $category = $path->category;
        $region = $path->region;

        $page = $request->get('page', 1);
        $perPage = 20;

        // ES orqali aqlli qidiruvni ishga tushirish qismi
        $adverts = $this->search->search($category, $region, $request, $perPage, $page);

        $regions = $region
            ? $region->children()->orderBy('name')->getModels()
            : Region::roots()->orderBy('name')->getModels();

        $categories = $category
            ? $category->children()->orderBy('name')->getModels()
            : Category::roots()->orderBy('name')->getModels();

        return view('adverts.index', compact('category', 'region', 'categories', 'regions', 'adverts'));
    }
}
