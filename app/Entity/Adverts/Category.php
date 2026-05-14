<?php

namespace App\Entity\Adverts;

use Database\Factories\AdvertsCategoryFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property int $depth
 * @property Category|null $parent
 * @property Category[] $children
 * @property Attribute[] $attributes
 */
class Category extends Model
{
    use HasFactory;
    use NodeTrait;

    protected $table = 'advert_categories';

    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'parent_id'];

    protected static function newFactory(): AdvertsCategoryFactory
    {
        return AdvertsCategoryFactory::new();
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class, 'category_id');
    }

    public function allAttributes(): Collection
    {
        $ids = array_merge($this->getAncestors()->pluck('id')->toArray(), [$this->id]);

        return Attribute::whereIn('category_id', $ids)
            ->orderBy('sort')
            ->get();
    }
}
