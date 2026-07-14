<?php

namespace App\Http\Controllers\Admin\Page;

use App\Entity\Page\Page;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pages\PageRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PageController extends Controller
{
    private function cleanContent(?string $html): string
    {
        return clean($html, [
            'HTML.Allowed' => 'p,br,b,strong,i,em,u,s,a[href|title|target],'
                . 'ul,ol,li,span[style],div[style],'
                . 'img[src|alt|width|height|style],'
                . 'h1,h2,h3,h4,h5,h6,blockquote,pre,code,hr,'
                . 'table,thead,tbody,tr,td[style],th[style]',
            'CSS.AllowedProperties' => 'width,height,text-align,float,margin,color,background-color,font-size,font-weight',
        ]);
    }

    public function index(): View
    {
        Gate::authorize('admin-panel');
        $pages = Page::defaultOrder()->withDepth()->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        Gate::authorize('admin-panel');
        $parents = Page::defaultOrder()->withDepth()->get();
        return view('admin.pages.create', compact('parents'));
    }

    public function store(PageRequest $request): RedirectResponse
    {
        Gate::authorize('admin-panel');

        $page = Page::create([
            'title'       => $request->input('title'),
            'menu_title'  => $request->input('menu_title'),
            'slug'        => $request->input('slug'),
            'content'     => $this->cleanContent($request->input('content')),
            'description' => $request->input('description'),
            'parent_id'   => $request->input('parent') ?: null,
        ]);

        return redirect()->route('admin.pages.show', $page)->with('success', 'Sahifa yaratildi.');
    }

    public function show(Page $page): View
    {
        Gate::authorize('admin-panel');
        return view('admin.pages.show', compact('page'));
    }

    public function edit(Page $page): View
    {
        Gate::authorize('admin-panel');
        $parents = Page::defaultOrder()->withDepth()
            ->whereNotDescendantOf($page)
            ->where('id', '!=', $page->id)
            ->get();
        return view('admin.pages.edit', compact('page', 'parents'));
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        Gate::authorize('admin-panel');

        $page->title       = $request->input('title');
        $page->menu_title  = $request->input('menu_title');
        $page->slug        = $request->input('slug');
        $page->content     = $this->cleanContent($request->input('content'));
        $page->description = $request->input('description');

        if ($request->input('parent')) {
            $page->appendToNode(Page::findOrFail($request->input('parent')));
        } else {
            $page->makeRoot();
        }
        $page->save();

        return redirect()->route('admin.pages.show', $page)->with('success', 'Saqlandi.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'O\'chirildi.');
    }

    public function up(Page $page): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $page->up();
        return back();
    }

    public function down(Page $page): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $page->down();
        return back();
    }

    public function uploadImage(Request $request): JsonResponse
    {
        Gate::authorize('admin-panel');

        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $path = $request->file('file')->store('pages', 'public');

        return response()->json(['url' => Storage::url($path)]);
    }
}
