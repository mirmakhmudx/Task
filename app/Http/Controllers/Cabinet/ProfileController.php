<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
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
        $user = $request->user();

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255',
                        Rule::unique('users')->ignore($user->id)],
        ]);

        if ($user->email !== $request->email) {
            $user->email_verified_at = null;
        }

        $user->fill([
            'name'  => $request->name,
            'email' => $request->email,
        ])->save();

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
