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

        foreach (Page::where('slug', $slug)->get() as $page) {
            if ($page->getPath() === $path) {
                $custom = 'pages.' . $page->slug;

                return view()->exists($custom)
                    ? view($custom, compact('page'))
                    : view('pages.show', compact('page'));
            }
        }

        abort(404);
    }
}
