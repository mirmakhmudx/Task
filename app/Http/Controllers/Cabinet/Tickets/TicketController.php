<?php

namespace App\Http\Controllers\Cabinet\Tickets;

use App\Entity\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Tickets\CreateRequest;
use App\Http\Requests\Cabinet\Tickets\MessageRequest;
use App\UseCases\Tickets\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $service
    ) {}

    // O'z tiketlari ro'yxati
    public function index(): View
    {
        $tickets = Ticket::forUser(Auth::user())
            ->orderByDesc('id')
            ->paginate(20);

        return view('cabinet.tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('cabinet.tickets.create');
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $ticket = $this->service->create(
            Auth::id(),
            $request->input('subject'),
            $request->input('content'),
        );

        return redirect()->route('cabinet.tickets.show', $ticket);
    }

    public function show(Ticket $ticket): View
    {
        $this->checkAccess($ticket);
        $ticket->load(['statuses.user', 'messages.user']);

        return view('cabinet.tickets.show', compact('ticket'));
    }

    // Xabar yuborish
    public function message(MessageRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->checkAccess($ticket);
        $this->service->sendMessage($ticket->id, Auth::id(), $request->input('message'));

        return redirect()->route('cabinet.tickets.show', $ticket);
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->checkAccess($ticket);
        $this->service->remove($ticket->id);

        return redirect()->route('cabinet.tickets.index')->with('success', 'Ticket deleted.');
    }

    // Faqat egasi ko'ra/boshqara olsin
    private function checkAccess(Ticket $ticket): void
    {
        if (!$ticket->isOwnedBy(Auth::user())) {
            abort(403);
        }
    }
}
