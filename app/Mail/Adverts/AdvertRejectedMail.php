<?php

namespace App\Mail\Adverts;

use App\Entity\Adverts\Advert;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdvertRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Advert $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }

    public function build(): static
    {
        return $this
            ->subject("E'loningiz rad etildi — " . config('app.name'))
            ->markdown('emails.adverts.rejected');
    }
}
