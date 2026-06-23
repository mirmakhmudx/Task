<?php

namespace App\Entity\Adverts\Dialog;

use App\Entity\Adverts\Advert;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $advert_id
 * @property int $user_id
 * @property int $client_id
 * @property int $user_new_messages
 * @property int $client_new_messages
 */
class Dialog extends Model
{
    protected $table   = 'advert_dialogs';
    protected $guarded = ['id'];

    // Dialogga xabar yozish. Kim yozganiga qarab qarama-qarshi tomonning
    // "o'qilmagan" hisoblagichi oshadi.
    public function writeMessage(int $fromUserId, string $message): Message
    {
        $msg = $this->messages()->create([
            'user_id' => $fromUserId,
            'message' => $message,
        ]);

        if ($fromUserId === $this->user_id) {
            // egasi yozdi -> xaridorda yangi xabar
            $this->increment('client_new_messages');
        } else {
            // xaridor yozdi -> egasida yangi xabar
            $this->increment('user_new_messages');
        }

        return $msg;
    }

    // Egasi o'qidi -> uning hisoblagichi 0
    public function readByOwner(): void
    {
        $this->update(['user_new_messages' => 0]);
    }

    // Xaridor o'qidi -> uning hisoblagichi 0
    public function readByClient(): void
    {
        $this->update(['client_new_messages' => 0]);
    }

    // ===== Relations =====
    public function advert(): BelongsTo  { return $this->belongsTo(Advert::class); }
    public function owner(): BelongsTo   { return $this->belongsTo(User::class, 'user_id'); }
    public function client(): BelongsTo  { return $this->belongsTo(User::class, 'client_id'); }
    public function messages(): HasMany  { return $this->hasMany(Message::class)->orderBy('id'); }
}
