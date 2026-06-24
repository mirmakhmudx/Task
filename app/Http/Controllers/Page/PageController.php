<?php

namespace App\Http\Controllers\Page;

use App\Entity\Page\Page;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $path): View
    {
        $path  = trim($path, '/');
        $slugs = explode('/', $path);
        $slug  = end($slugs);

        // oxirgi slug bo'yicha nomzodlarni olamiz, to'liq yo'li mos kelganini tanlaymiz
        foreach (Page::where('slug', $slug)->get() as $page) {
            if ($page->getPath() === $path) {
                return view('pages.show', compact('page'));
            }
        }

        abort(404);
    }
}
