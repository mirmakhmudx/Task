<?php

namespace App\Http\Controllers\MainPage\HomeController;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home');
    }
}
