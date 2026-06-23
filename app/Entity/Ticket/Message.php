<?php

namespace App\Entity\Ticket;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $message
 */
class Message extends Model
{
    protected $table   = 'ticket_ticket_messages';
    protected $guarded = ['id'];

    public function user(): BelongsTo   { return $this->belongsTo(User::class); }
    public function ticket(): BelongsTo { return $this->belongsTo(Ticket::class); }
}
