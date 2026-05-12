<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Services\Sms\SmsSender;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PhoneController extends Controller
{
    public function __construct(
        private readonly SmsSender $sms
    ) {}

    public function request(): RedirectResponse
    {
        $user = Auth::user();

        try {
            $token = $user->requestPhoneVerification(Carbon::now());
            $user->save(); // modeldan chiqarildi, shu yerda saqlaymiz

            $this->sms->send(
                $user->phone,
                "Your verification code: {$token}"
            );
        } catch (\DomainException $e) {
            return back()->withErrors(['phone' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.profile.phone')
            ->with('info', 'SMS code sent.');
    }

    public function form(): View
    {
        return view('cabinet.profile.phone', [
            'user' => Auth::user(),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        try {
            $user->verifyPhone($request->token, Carbon::now());
            $user->save(); // modeldan chiqarildi, shu yerda saqlaymiz
        } catch (\DomainException $e) {
            return back()->withErrors(['token' => $e->getMessage()]);
        }

        return redirect()->route('cabinet.profile.show')
            ->with('success', 'Phone verified successfully.');
    }
}
