<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Dialog\Dialog;
use Illuminate\Support\Facades\DB;

class DialogService
{
    // Xaridor e'lon egasiga yozadi
    public function writeClientMessage(Advert $advert, int $fromUserId, string $message): void
    {
        DB::transaction(function () use ($advert, $fromUserId, $message) {
            $dialog = $this->getOrCreateDialog($advert, $fromUserId);
            $dialog->writeMessage($fromUserId, $message);
        });
    }

    // Egasi xaridorga javob yozadi
    public function writeOwnerMessage(Advert $advert, int $toUserId, string $message): void
    {
        DB::transaction(function () use ($advert, $toUserId, $message) {
            $dialog = $this->getOrCreateDialog($advert, $toUserId);
            $dialog->writeMessage($advert->user_id, $message);
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

    // E'lon + xaridor bo'yicha dialogni topadi yoki yangisini ochadi
    private function getOrCreateDialog(Advert $advert, int $clientId): Dialog
    {
        return Dialog::firstOrCreate(
            ['advert_id' => $advert->id, 'client_id' => $clientId],
            ['user_id' => $advert->user_id],
        );
    }

    private function getDialog(int $id): Dialog
    {
        return Dialog::findOrFail($id);
    }
}
