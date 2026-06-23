<?php

namespace App\Http\Controllers\Cabinet\Dialogs;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Dialog\Dialog;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Dialogs\WriteRequest;
use App\UseCases\Adverts\DialogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DialogController extends Controller
{
    public function __construct(
        private readonly DialogService $service
    ) {}

    // Barcha dialoglar (egasi yoki xaridor sifatida)
    public function index(): View
    {
        $userId = Auth::id();

        $dialogs = Dialog::with(['advert', 'owner', 'client'])
            ->where(fn ($q) => $q->where('user_id', $userId)->orWhere('client_id', $userId))
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('cabinet.dialogs.index', compact('dialogs'));
    }

    // E'lon sahifasidan "sotuvchiga yozish"
    public function write(WriteRequest $request, Advert $advert): RedirectResponse
    {
        if ($advert->user_id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot message your own advert.']);
        }

        $this->service->writeClientMessage($advert, Auth::id(), $request->input('message'));

        $dialog = Dialog::where('advert_id', $advert->id)
            ->where('client_id', Auth::id())
            ->firstOrFail();

        return redirect()->route('cabinet.dialogs.show', $dialog);
    }

    // Dialogni ko'rish (+ o'qilgan deb belgilash)
    public function show(Dialog $dialog): View
    {
        $this->checkAccess($dialog);

        if (Auth::id() === $dialog->user_id) {
            $this->service->readByOwner($dialog->id);
        } else {
            $this->service->readByClient($dialog->id);
        }

        $dialog->load(['advert', 'owner', 'client', 'messages.user']);

        return view('cabinet.dialogs.show', compact('dialog'));
    }

    // Dialog ichida xabar yuborish
    public function message(WriteRequest $request, Dialog $dialog): RedirectResponse
    {
        $this->checkAccess($dialog);
        $message = $request->input('message');

        if (Auth::id() === $dialog->user_id) {
            // egasi javob beradi
            $this->service->writeOwnerMessage($dialog->advert, $dialog->client_id, $message);
        } else {
            // xaridor yozadi
            $this->service->writeClientMessage($dialog->advert, Auth::id(), $message);
        }

        return redirect()->route('cabinet.dialogs.show', $dialog);
    }

    // Faqat egasi yoki xaridor kira oladi
    private function checkAccess(Dialog $dialog): void
    {
        $userId = Auth::id();
        if ($userId !== $dialog->user_id && $userId !== $dialog->client_id) {
            abort(403);
        }
    }
}
