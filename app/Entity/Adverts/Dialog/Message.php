<?php

namespace App\Entity\Adverts\Dialog;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $dialog_id
 * @property int $user_id
 * @property string $message
 */
class Message extends Model
{
    protected $table   = 'advert_dialog_messages';
    protected $guarded = ['id'];

    public function user(): BelongsTo   { return $this->belongsTo(User::class); }
    public function dialog(): BelongsTo { return $this->belongsTo(Dialog::class); }
}
