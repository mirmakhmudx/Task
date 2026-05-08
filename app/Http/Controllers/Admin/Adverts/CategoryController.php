<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Adverts\CategoryStoreRequest;
use App\Http\Requests\Admin\Adverts\CategoryUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        Gate::authorize('admin-panel');

        $categories = Category::defaultOrder()->withDepth()->get();

        return view('admin.adverts.categories.index', compact('categories'));
    }

    public function create(): View
    {
        Gate::authorize('admin-panel');

        $parents = Category::defaultOrder()->withDepth()->get();

        return view('admin.adverts.categories.create', compact('parents'));
    }

    public function store(CategoryStoreRequest $request): RedirectResponse
    {
        Gate::authorize('admin-panel');

        $category = Category::create([
            'name'      => $request->name,
            'slug'      => $request->slug,
            'parent_id' => $request->parent ?: null,
        ]);

        return redirect()->route('admin.adverts.categories.show', $category)
            ->with('success', 'Kategoriya yaratildi.');
    }

    public function show(Category $category): View
    {
        Gate::authorize('admin-panel');

        $children = $category->children()->defaultOrder()->withDepth()->get();

        return view('admin.adverts.categories.show', compact('category', 'children'));
    }

    public function edit(Category $category): View
    {
        Gate::authorize('admin-panel');

        $parents = Category::defaultOrder()
            ->withDepth()
            ->whereNotDescendantOf($category)
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.adverts.categories.edit', compact('category', 'parents'));
    }

    public function update(CategoryUpdateRequest $request, Category $category): RedirectResponse
    {
        Gate::authorize('admin-panel');

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('admin.adverts.categories.show', $category)
            ->with('success', 'Saqlandi.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('admin-panel');

        $category->delete();

        return redirect()->route('admin.adverts.categories.index')
            ->with('success', 'O\'chirildi.');
    }
}
