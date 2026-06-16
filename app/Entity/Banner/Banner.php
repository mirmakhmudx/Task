<?php

namespace App\Entity\Banner;

use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    public const STATUS_DRAFT      = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_WAIT_PAY   = 'wait_pay';
    public const STATUS_ACTIVE     = 'active';
    public const STATUS_CLOSED     = 'closed';

    protected $table   = 'banner_banners';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public static function formatsList(): array
    {
        return [
            '240x400' => '240x400',
            '240x600' => '240x600',
            '728x90'  => '728x90',
            '300x250' => '300x250',
        ];
    }

    public function isDraft(): bool        { return $this->status === self::STATUS_DRAFT; }
    public function isOnModeration(): bool { return $this->status === self::STATUS_MODERATION; }
    public function isWaitPay(): bool      { return $this->status === self::STATUS_WAIT_PAY; }
    public function isActive(): bool       { return $this->status === self::STATUS_ACTIVE; }
    public function isClosed(): bool       { return $this->status === self::STATUS_CLOSED; }

    public function sendToModeration(): void
    {
        if (!$this->isDraft()) {
            throw new \DomainException('Banner is not draft.');
        }
        if (!$this->file) {
            throw new \DomainException('Upload the banner file first.');
        }
        $this->update(['status' => self::STATUS_MODERATION]);
    }

    public function moderate(): void
    {
        if (!$this->isOnModeration()) {
            throw new \DomainException('Banner is not sent to moderation.');
        }
        $this->update([
            'status'        => self::STATUS_WAIT_PAY,
            'reject_reason' => null,
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status'        => self::STATUS_DRAFT,
            'reject_reason' => $reason,
        ]);
    }

    public function pay(Carbon $date): void
    {
        if (!$this->isWaitPay()) {
            throw new \DomainException('Banner is not waiting for payment.');
        }
        $this->update([
            'status'       => self::STATUS_ACTIVE,
            'published_at' => $date,
        ]);
    }

    // Ko'rsatishni sanash: views +1, limitga yetsa yopiladi
    public function view(): void
    {
        $this->increment('views');
        if ($this->views >= $this->limit) {
            $this->update(['status' => self::STATUS_CLOSED]);
        }
    }

    // Bosishni sanash: clicks +1
    public function click(): void
    {
        $this->increment('clicks');
    }

    public function changeFile(string $file): void
    {
        $this->update(['file' => $file]);
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function user(): BelongsTo     { return $this->belongsTo(User::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function region(): BelongsTo   { return $this->belongsTo(Region::class); }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }
}
