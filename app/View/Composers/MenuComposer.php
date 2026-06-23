<?php

namespace App\View\Composers;

use App\Entity\Page\Page;
use Illuminate\View\View;

class MenuComposer
{
    // Navbar'da nechta sahifa to'g'ridan-to'g'ri chiqadi (ortig'i "More..." ga tushadi)
    private const INLINE_LIMIT = 3;

    public function compose(View $view): void
    {
        // Faqat root (ota'siz) sahifalar — nestedset tartibida
        $pages = Page::whereIsRoot()->defaultOrder()->get();

        $view->with('menuMain', $pages->take(self::INLINE_LIMIT));
        $view->with('menuMore', $pages->slice(self::INLINE_LIMIT));
    }
}
