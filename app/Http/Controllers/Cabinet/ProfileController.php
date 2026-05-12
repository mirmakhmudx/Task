<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('cabinet.profile.show', ['user' => Auth::user()]);
    }

    public function edit(): View
    {
        return view('cabinet.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:255', 'regex:/^\+?\d+$/'],
        ]);

        $user = $request->user();

        $data = [
            'name'      => $request->name,
            'last_name' => $request->last_name,
            'phone'     => $request->phone,
        ];

        // Telefon o'zgarganda verified ni tozala
        if ($user->phone !== $request->phone) {
            $data['phone_verified']      = false;
            $data['phone_verify_token']  = null;
            $data['phone_verify_token_expire'] = null;
        }

        $user->fill($data)->save();

        return redirect()->route('cabinet.profile.show')
            ->with('success', 'Profile yangilandi.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
