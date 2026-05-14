<?php

namespace App\Entity;

use Database\Factories\RegionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property Region|null $parent
 * @property Region[] $children
 */
class Region extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id'];

    protected static function newFactory(): RegionFactory
    {
        return RegionFactory::new();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    public function getAddress(): string
    {
        return ($this->parent ? $this->parent->getAddress() . ', ' : '') . $this->name;
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }
}
