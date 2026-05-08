<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function dashboard()
    {
        return view('admin.home.home');
    }
    public function index()
    {
        return view('home');
    }
}
