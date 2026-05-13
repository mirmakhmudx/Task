<?php

namespace App\Entity\Adverts;

use App\Entity\Region;
use App\Models\User;
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
 * @property \Carbon\Carbon|null $published_at
 * @property \Carbon\Carbon|null $expires_at
 */
class Advert extends Model
{
    public const STATUS_DRAFT      = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_ACTIVE     = 'active';
    public const STATUS_CLOSED     = 'closed';

    protected $table = 'advert_adverts';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'expires_at'   => 'datetime',
        ];
    }

    public function isDraft(): bool      { return $this->status === self::STATUS_DRAFT; }
    public function isModeration(): bool { return $this->status === self::STATUS_MODERATION; }
    public function isActive(): bool     { return $this->status === self::STATUS_ACTIVE; }
    public function isClosed(): bool     { return $this->status === self::STATUS_CLOSED; }

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
}
