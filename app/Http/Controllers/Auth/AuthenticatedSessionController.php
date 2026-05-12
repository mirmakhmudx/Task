<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\Sms\SmsSender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly SmsSender $sms
    ) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Waiting foydalanuvchi login qila olmaydi
        if ($user->isWait()) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'You need to confirm your account. Please check your email.',
            ]);
        }

        // Two Factor Auth yoqilgan bo'lsa
        if ($user->isTwoFactorEnabled()) {
            Auth::logout();

            $token = (string) random_int(10000, 99999);

            $request->session()->put('auth', [
                'id'       => $user->id,
                'token'    => $token,
                'remember' => $request->filled('remember'),
            ]);

            $this->sms->send($user->phone, 'Login code: ' . $token);

            return redirect()->route('login.phone');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Two Factor - SMS kod formasi
    public function phoneForm(): View
    {
        return view('auth.phone');
    }

    // Two Factor - SMS kod tekshirish
    public function phoneVerify(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        if (!$session = $request->session()->get('auth')) {
            abort(400, 'Missing token info.');
        }

        /** @var User $user */
        $user = User::findOrFail($session['id']);

        if ($request->token === $session['token']) {
            $request->session()->flush();

            Auth::login($user, $session['remember']);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'token' => 'Invalid auth token.',
        ]);
    }
}
