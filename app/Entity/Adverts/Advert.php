<?php

namespace App\Entity\Adverts;

use App\Entity\Region\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int|null $region_id
 * @property string $title
 * @property string $content
 * @property int|null $price
 * @property string|null $address
 * @property string $status
 * @property string|null $reject_reason
 * @property Carbon|null $published_at
 * @property Carbon|null $expires_at
 */
class Advert extends Model
{
    public const STATUS_DRAFT      = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_ACTIVE     = 'active';
    public const STATUS_CLOSED     = 'closed';

    protected $table   = 'advert_adverts';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'expires_at'   => 'datetime',
        ];
    }

    // ==================== STATUS CHECKS ====================

    public function isDraft(): bool      { return $this->status === self::STATUS_DRAFT; }
    public function isModeration(): bool { return $this->status === self::STATUS_MODERATION; }
    public function isActive(): bool     { return $this->status === self::STATUS_ACTIVE; }
    public function isClosed(): bool     { return $this->status === self::STATUS_CLOSED; }

    // ==================== BIZNES METODLAR ====================

    public function sendToModeration(): void
    {
        if (!$this->isDraft()) {
            throw new \DomainException('Advert is not draft.');
        }
        if (!$this->photos()->count()) {
            throw new \DomainException('Upload at least one photo.');
        }
        $this->update(['status' => self::STATUS_MODERATION]);
    }

    public function moderate(Carbon $date): void
    {
        if (!$this->isModeration()) {
            throw new \DomainException('Advert is not sent to moderation.');
        }
        $this->update([
            'published_at' => $date,
            'expires_at'   => $date->copy()->addDays(30),
            'status'       => self::STATUS_ACTIVE,
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status'        => self::STATUS_DRAFT,
            'reject_reason' => $reason,
        ]);
    }

    public function expire(): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('Advert is not active.');
        }
        $this->update(['status' => self::STATUS_CLOSED]);
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(Value::class, 'advert_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class, 'advert_id');
    }


    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeForCategory(Builder $query, Category $category): Builder
    {
        return $query->where('category_id', array_merge(
            [$category->id],
            $category->descendants()->pluck('id')->toArray()
        ));
    }
}
