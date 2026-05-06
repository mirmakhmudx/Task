<?php

namespace App\Http\Controllers\Auth;

use App\CommandBus;
use App\Console\Commands\User\Auth\Register\Command;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly CommandBus $bus
    ) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = $this->bus->handle(new Command(
            name:     $request->validated('name'),
            email:    $request->validated('email'),
            password: $request->validated('password'),
        ));

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Xush kelibsiz, ' . $user->name . '!');
    }
}
