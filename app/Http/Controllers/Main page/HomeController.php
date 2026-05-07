<?php

namespace App\Http\Controllers\main page;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home');
    }
}
