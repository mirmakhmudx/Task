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
        return view('cabinet.profile.show', [
            'user' => Auth::user(),
        ]);
    }

    public function edit(): View
    {
        return view('cabinet.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(\App\Http\Requests\Cabinet\ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $user->update([
            'name'      => $request->name,
            'last_name' => $request->last_name,
        ]);

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
