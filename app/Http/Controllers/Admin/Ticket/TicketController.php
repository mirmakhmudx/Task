<?php

namespace App\Http\Controllers\Admin\Ticket;

use App\Entity\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tickets\EditRequest;
use App\Http\Requests\Admin\Tickets\MessageRequest;
use App\UseCases\Tickets\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $service
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('admin-panel');

        $query = Ticket::with('user')->orderByDesc('id');

        if ($id = $request->input('id')) {
            $query->where('id', $id);
        }

        if ($user = $request->input('user')) {
            if (ctype_digit((string) $user)) {
                $query->where('user_id', $user);
            } else {
                $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$user}%"));
            }
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $tickets  = $query->paginate(20)->withQueryString();
        $statuses = Ticket::statusesList();

        return view('admin.tickets.index', compact('tickets', 'statuses'));
    }

    public function show(Ticket $ticket): View
    {
        Gate::authorize('admin-panel');
        $ticket->load(['user', 'statuses.user', 'messages.user']);

        return view('admin.tickets.show', compact('ticket'));
    }

    public function editForm(Ticket $ticket): View
    {
        Gate::authorize('admin-panel');

        return view('admin.tickets.edit', compact('ticket'));
    }

    public function update(EditRequest $request, Ticket $ticket): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $this->service->edit($ticket->id, $request->input('subject'), $request->input('content'));

        return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Saved.');
    }

    public function approve(Ticket $ticket): RedirectResponse
    {
        Gate::authorize('admin-panel');
        try {
            $this->service->approve($ticket->id, Auth::id());
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.tickets.show', $ticket);
    }

    public function close(Ticket $ticket): RedirectResponse
    {
        Gate::authorize('admin-panel');
        try {
            $this->service->close($ticket->id, Auth::id());
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.tickets.show', $ticket);
    }

    public function message(MessageRequest $request, Ticket $ticket): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $this->service->sendMessage($ticket->id, Auth::id(), $request->input('message'));

        return redirect()->route('admin.tickets.show', $ticket);
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $this->service->remove($ticket->id);

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket deleted.');
    }
}
