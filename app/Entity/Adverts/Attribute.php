<?php

namespace App\Entity\Adverts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $type
 * @property bool $required
 * @property string|null $default
 * @property array|null $variants
 * @property int $sort
 * @property Category $category
 */
class Attribute extends Model
{
    public const TYPE_STRING  = 'string';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_FLOAT   = 'float';

    public $timestamps = false;

    protected $table = 'advert_attributes';

    protected $fillable = [
        'name', 'type', 'required',
        'default', 'variants', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'variants' => 'array',
            'required' => 'boolean',
        ];
    }

    public static function typesList(): array
    {
        return [
            self::TYPE_STRING  => 'String',
            self::TYPE_INTEGER => 'Integer',
            self::TYPE_FLOAT   => 'Float',
        ];
    }

    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }

    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INTEGER;
    }

    public function isFloat(): bool
    {
        return $this->type === self::TYPE_FLOAT;
    }

    public function isSelect(): bool
    {
        $variants = $this->variants;
        return is_array($variants) && count($variants) > 0;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
