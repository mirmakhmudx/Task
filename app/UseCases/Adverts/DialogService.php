<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Dialog\Dialog;
use App\Mail\Adverts\DialogMessageMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DialogService
{
    public function writeClientMessage(Advert $advert, int $fromUserId, string $message): void
    {
        DB::transaction(function () use ($advert, $fromUserId, $message) {
            $dialog = $this->getOrCreateDialog($advert, $fromUserId);
            $dialog->writeMessage($fromUserId, $message);

            $sender    = User::find($fromUserId);
            $recipient = $dialog->owner;
            if ($recipient && $recipient->email) {
                Mail::to($recipient->email)->queue(new DialogMessageMail($dialog->load('advert'), $sender, $message));
            }
        });
    }

    public function writeOwnerMessage(Advert $advert, int $toUserId, string $message): void
    {
        DB::transaction(function () use ($advert, $toUserId, $message) {
            $dialog = $this->getOrCreateDialog($advert, $toUserId);
            $dialog->writeMessage($advert->user_id, $message);

            $sender    = User::find($advert->user_id);
            $recipient = $dialog->client;
            if ($recipient && $recipient->email) {
                Mail::to($recipient->email)->queue(new DialogMessageMail($dialog->load('advert'), $sender, $message));
            }
        });
    }

    public function readByOwner(int $dialogId): void
    {
        $this->getDialog($dialogId)->readByOwner();
    }

    public function readByClient(int $dialogId): void
    {
        $this->getDialog($dialogId)->readByClient();
    }

    private function getOrCreateDialog(Advert $advert, int $clientId): Dialog
    {
        return Dialog::firstOrCreate(
            ['advert_id' => $advert->id, 'client_id' => $clientId],
            ['user_id' => $advert->user_id],
        );
    }

    private function getDialog(int $id): Dialog
    {
        return Dialog::with(['owner', 'client'])->findOrFail($id);
    }
}
