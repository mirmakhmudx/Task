<?php

namespace App\Services\Auth;

use App\Entity\User;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Auth\Events\Registered;
use App\Mail\Auth\VerifyMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class RegisterService
{
    public function __construct(
        private Mailer $mailer,
        private Dispatcher $dispatcher
    ) {}


    public function handle(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role'         => User::ROLE_USER,   // ← shu yo'q edi
            'verify_token' => Str::random(40),
            'status' => User::STATUS_WAIT,
        ]);

        Mail::to($user->email)->send(new VerifyMail($user));

        return $user;
    }


    public function verify($id): void
    {
        $user = User::findOrFail($id);
        $user->verify();
        $user->save();
        $this->mailer->to($user->email)->send(new SuccessVerifiedMail($user));
    }
}
