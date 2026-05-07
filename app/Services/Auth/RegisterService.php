<?php

namespace App\Services\Auth;

use App\Entity\User\User;
use App\Mail\Auth\VerifyMail;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
