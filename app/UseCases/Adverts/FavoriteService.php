<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Advert;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FavoriteService
{
    public function add(int $userId, int $advertId): void
    {
        $advert = $this->getAdvert($advertId);

        $exists = DB::table('advert_user_favorites')
            ->where('user_id', $userId)
            ->where('advert_id', $advert->id)
            ->exists();

        if ($exists) {
            throw new \DomainException('This advert is already added to favorites.');
        }

        DB::table('advert_user_favorites')->insert([
            'user_id'    => $userId,
            'advert_id'  => $advert->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function remove(int $userId, int $advertId): void
    {
        DB::table('advert_user_favorites')
            ->where('user_id', $userId)
            ->where('advert_id', $advertId)
            ->delete();
    }

    private function getAdvert(int $advertId): Advert
    {
        return Advert::findOrFail($advertId);
    }
}
