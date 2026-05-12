<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorLoginController extends Controller
{
    public function form()
    {
        return view('auth.two-factor');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        $auth = $request->session()->get('auth');

        if (!$auth || !isset($auth['id'], $auth['token'])) {
            return redirect()->route('login');
        }

        if ($request->token !== (string) $auth['token']) {
            return back()->withErrors(['token' => 'Token noto\'g\'ri.']);
        }

        $user = User::findOrFail($auth['id']);

        Auth::login($user, $auth['remember'] ?? false);
        $request->session()->forget('auth');

        return redirect()->route('dashboard');
    }
}
