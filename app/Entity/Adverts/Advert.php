<?php

namespace App\Entity\Adverts;

use App\Entity\Region\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int|null $region_id
 * @property string $title
 * @property string $content
 * @property string $status
 * @property int|null $price
 * @property string|null $address
 */
class Advert extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_MODERATE = 'moderate';

    protected $table = 'adverts';
    protected $fillable = [
        'user_id', 'category_id', 'region_id',
        'title', 'content', 'status',
        'price', 'address', 'latitude', 'longitude',
        'published_at', 'expires_at',
    ];

    protected function casts()
    {
        return ['published_at' => 'datetime', 'expires_at' => 'datetime'];
    }

    public function isDraft():bool {return $this->status === self::STATUS_DRAFT;}
    public function isActive():bool {return $this->status === self::STATUS_ACTIVE;}
    public function isClosed():bool {return $this->status === self::STATUS_CLOSED;}
    public function isModerate():bool {return $this->status === self::STATUS_MODERATE;}

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function region():BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

}



