<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PhoneController extends Controller
{
    public function request(): RedirectResponse
    {
        $user = Auth::user();

        try {
            $token = $user->requestPhoneVerification(Carbon::now());

        } catch (\DomainException $e) {
            return back()->withErrors(['phone' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.profile.phone')
            ->with('info', 'SMS code sent. (dev: ' . $token . ')');
    }

    public function form(): View
    {
        return view('cabinet.profile.phone');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        $user = Auth::user();

        try {
            $user->verifyPhone($request->token, Carbon::now());
        } catch (\DomainException $e) {
            return back()->withErrors(['token' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.profile.show')->with('success', 'Phone verified successfully.');
    }
}
