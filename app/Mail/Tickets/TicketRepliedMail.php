<?php

namespace App\Mail\Tickets;

use App\Entity\Ticket\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketRepliedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Ticket $ticket;
    public string $replyText;

    public function __construct(Ticket $ticket, string $replyText)
    {
        $this->ticket    = $ticket;
        $this->replyText = $replyText;
    }

    public function build(): static
    {
        return $this
            ->subject("Murojaatingizga javob — " . config('app.name'))
            ->markdown('emails.tickets.replied');
    }
}
