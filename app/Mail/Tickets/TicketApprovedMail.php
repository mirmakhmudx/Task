<?php

namespace App\Mail\Tickets;

use App\Entity\Ticket\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build(): static
    {
        return $this
            ->subject("Murojaatingiz ko'rib chiqildi — " . config('app.name'))
            ->markdown('emails.tickets.approved');
    }
}
