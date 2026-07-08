<?php

namespace App\UseCases\Tickets;

use App\Entity\Ticket\Ticket;
use App\Mail\Tickets\TicketApprovedMail;
use App\Mail\Tickets\TicketRepliedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TicketService
{
    public function create(int $userId, string $subject, string $content): Ticket
    {
        return DB::transaction(fn () => Ticket::new($userId, $subject, $content));
    }

    public function edit(int $id, string $subject, string $content): void
    {
        $this->getTicket($id)->update(['subject' => $subject, 'content' => $content]);
    }

    public function approve(int $id, int $byUserId): void
    {
        DB::transaction(function () use ($id, $byUserId) {
            $ticket = $this->getTicket($id);
            $ticket->approve($byUserId);
            Mail::to($ticket->user->email)->queue(new TicketApprovedMail($ticket));
        });
    }

    public function close(int $id, int $byUserId): void
    {
        DB::transaction(function () use ($id, $byUserId) {
            $this->getTicket($id)->close($byUserId);
        });
    }

    public function sendMessage(int $id, int $fromUserId, string $message): void
    {
        $ticket = $this->getTicket($id);
        $ticket->addMessage($fromUserId, $message);

        // Agar admin/operator yozgan bo'lsa, foydalanuvchiga xabar yuboramiz
        $isAdminReply = $fromUserId !== $ticket->user_id;
        if ($isAdminReply && $ticket->user->email) {
            Mail::to($ticket->user->email)->queue(new TicketRepliedMail($ticket, $message));
        }
    }

    public function remove(int $id): void
    {
        $this->getTicket($id)->delete();
    }

    private function getTicket(int $id): Ticket
    {
        return Ticket::with('user')->findOrFail($id);
    }
}
