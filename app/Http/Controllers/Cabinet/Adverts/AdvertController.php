<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdvertController extends Controller
{
    public function index(): View
    {
        return view('cabinet.adverts.index');
    }

    public function create(): View
    {
        return view('cabinet.adverts.create');
    }
}
