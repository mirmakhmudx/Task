<?php

namespace App\Mail\Adverts;

use App\Entity\Adverts\Dialog\Dialog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DialogMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public Dialog $dialog;
    public User $sender;
    public string $messageText;

    public function __construct(Dialog $dialog, User $sender, string $messageText)
    {
        $this->dialog      = $dialog;
        $this->sender      = $sender;
        $this->messageText = $messageText;
    }

    public function build(): static
    {
        return $this
            ->subject("Yangi xabar — " . config('app.name'))
            ->markdown('emails.adverts.dialog-message');
    }
}
