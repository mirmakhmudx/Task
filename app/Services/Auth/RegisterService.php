<?php

namespace App\Services\Auth;

use App\Entity\User;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Auth\Events\Registered;

class RegisterService
{
    public function __construct(
        private Mailer $mailer,
        private Dispatcher $dispatcher
    ) {}


    public function handle(array $data): User
    {
        $user = User::register(
            $data['name'],
            $data['email'],
            $data['password']
        );

        $this->mailer->to($user->email)->send(new VerifyMail($user));
        $this->dispatcher->dispatch(new Registered($user));

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
