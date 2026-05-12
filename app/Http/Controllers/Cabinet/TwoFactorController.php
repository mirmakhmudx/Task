<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    /**
     * Two factor auth yoqish.
     */
    public function enable(): RedirectResponse
    {
        $user = Auth::user();

        try {
            $user->enableTwoFactor();
        } catch (\DomainException $e) {
            return back()->withErrors(['two_factor' => $e->getMessage()]);
        }

        return back()->with('success', 'Two factor auth enabled.');
    }

    /**
     * Two factor auth o'chirish.
     */
    public function disable(): RedirectResponse
    {
        $user = Auth::user();

        try {
            $user->disableTwoFactor();
        } catch (\DomainException $e) {
            return back()->withErrors(['two_factor' => $e->getMessage()]);
        }

        return back()->with('success', 'Two factor auth disabled.');
    }
}
