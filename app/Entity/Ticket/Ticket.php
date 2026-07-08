<?php

namespace App\Entity\Ticket;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $subject
 * @property string $content
 * @property string $status
 */
class Ticket extends Model
{
    public const STATUS_OPEN     = 'open';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_CLOSED   = 'closed';

    protected $table   = 'ticket_tickets';
    protected $guarded = ['id'];

    public static function statusesList(): array
    {
        return [
            self::STATUS_OPEN     => 'Open',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_CLOSED   => 'Closed',
        ];
    }

    // Yangi tiket (Open holatda) + birinchi status tarixi yozuvi
    public static function new(int $userId, string $subject, string $content): self
    {
        $ticket = static::create([
            'user_id' => $userId,
            'subject' => $subject,
            'content' => $content,
            'status'  => self::STATUS_OPEN,
        ]);
        $ticket->addStatus(self::STATUS_OPEN, $userId);
        return $ticket;
    }

    public function isOpen(): bool     { return $this->status === self::STATUS_OPEN; }
    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
    public function isClosed(): bool   { return $this->status === self::STATUS_CLOSED; }

    // Admin: tasdiqlash (faqat open bo'lsa)
    public function approve(int $byUserId): void
    {
        if (!$this->isOpen()) {
            throw new \DomainException('Ticket is not open.');
        }
        $this->changeStatus(self::STATUS_APPROVED, $byUserId);
    }

    // Yopish (closed bo'lmasa)
    public function close(int $byUserId): void
    {
        if ($this->isClosed()) {
            throw new \DomainException('Ticket is already closed.');
        }
        $this->changeStatus(self::STATUS_CLOSED, $byUserId);
    }

    private function changeStatus(string $status, int $byUserId): void
    {
        $this->update(['status' => $status]);
        $this->addStatus($status, $byUserId);
    }

    // Status tarixiga yangi yozuv qo'shadi
    private function addStatus(string $status, int $byUserId): void
    {
        $this->statuses()->create([
            'user_id'    => $byUserId,
            'status'     => $status,
            'created_at' => Carbon::now(),
        ]);
    }

    // Tiketga xabar qo'shish
    public function addMessage(int $userId, string $message): Message
    {
        return $this->messages()->create([
            'user_id' => $userId,
            'message' => $message,
        ]);
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    // ===== Relations =====
    public function user(): BelongsTo     { return $this->belongsTo(User::class); }
    public function statuses(): HasMany   { return $this->hasMany(Status::class)->orderBy('id'); }
    public function messages(): HasMany   { return $this->hasMany(Message::class)->orderBy('id'); }

    // ===== Scopes =====
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }
}
