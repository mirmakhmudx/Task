<?php

namespace App\Entity\Page;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property int $id
 * @property string $title
 * @property string $menu_title
 * @property string $slug
 * @property string|null $content
 * @property string|null $description
 * @property int|null $parent_id
 * @property int $depth
 */
class Page extends Model
{
    use NodeTrait;

    protected $table = 'pages';

    public $timestamps = false;

    protected $fillable = ['title', 'menu_title', 'slug', 'content', 'description', 'parent_id'];

    /** To'liq slug-yo'l: ota slug'lar + o'zi (masalan "about/company") */
    public function getPath(): string
    {
        $slugs = $this->getAncestors()->pluck('slug')->toArray();
        $slugs[] = $this->slug;

        return implode('/', $slugs);
    }
}
