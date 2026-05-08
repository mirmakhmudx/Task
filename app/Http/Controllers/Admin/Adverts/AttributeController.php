<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\Adverts\Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Entity\Adverts\Category;
use App\Http\Requests\Admin\Adverts\AttributeStoreRequest;
use App\Http\Requests\Admin\Adverts\AttributeUpdateStoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AttributeController extends Controller
{
    public function create(Category $category): View
    {
        Gate::authorize('admin-panel');
        $types = Attribute::typesList();
        return view('admin.adverts.categories.attributes.create', compact('category', 'types'));
    }

    public function store(AttributeStoreRequest $request, Category $category): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $category->attributes()->create($request->validated());
        return redirect()->route('admin.adverts.categories.show', $category)->with('success', 'Attribute qo\'shildi.');
    }

    public function edit(Category $category, Attribute $attribute): View
    {
        Gate::authorize('admin-panel');
        $types = Attribute::typesList();
        return view('admin.adverts.categories.attributes.edit', compact('category', 'attribute', 'types'));
    }

    public function update(AttributeUpdateStoreRequest $request, Category $category, Attribute $attribute): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $attribute->update($request->validated());
        return redirect()->route('admin.adverts.categories.show', $category)->with('success', 'Attribute qo\'shildi.');
    }

    public function destroy(Category $category, Attribute $attribute): RedirectResponse
    {
        Gate::authorize('admin-panel');
        $attribute->delete();
        return redirect()->route('admin.adverts.categories.show', $category)->with('success', 'O\'chirildi.');
    }

}
