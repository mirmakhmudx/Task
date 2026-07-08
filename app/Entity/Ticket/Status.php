<?php

namespace App\Entity\Ticket;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 */
class Status extends Model
{
    protected $table   = 'ticket_ticket_statuses';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
