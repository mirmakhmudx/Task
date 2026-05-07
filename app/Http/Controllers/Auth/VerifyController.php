<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class VerifyController extends Controller
{
    public function verify($token)
    {
        return redirect()->route('login')->with('success', 'Email tasdiqlandi!');
    }
}
