<?php

namespace App\Http\Controllers\Admin\Region;

use App\Entity\Region\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Regions\StoreRequest;
use App\Http\Requests\Admin\Regions\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function index(): View
    {
        Gate::authorize('admin-panel');

        $regions = Region::with('parent')->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.regions.index', compact('regions'));
    }

    public function create(): View
    {
        Gate::authorize('admin-panel');

        $parents = Region::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.regions.create', compact('parents'));
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Gate::authorize('admin-panel');

        $region = Region::create([
            'name'      => $request->name,
            'slug'      => $request->slug,
            'parent_id' => $request->parent_id ?: null,
        ]);

        return redirect()->route('admin.regions.show', $region)
            ->with('success', 'Region yaratildi.');
    }

    public function show(Region $region): View
    {
        Gate::authorize('admin-panel');

        $children = $region->children()->orderBy('name')->get();

        return view('admin.regions.show', compact('region', 'children'));
    }

    public function edit(Region $region): View
    {
        Gate::authorize('admin-panel');

        $parents = Region::whereNull('parent_id')->where('id', '!=', $region->id)
            ->orderBy('name')
            ->get();

        return view('admin.regions.edit', compact('region', 'parents'));
    }

    public function update(UpdateRequest $request, Region $region): RedirectResponse
    {
        Gate::authorize('admin-panel');

        $region->update([
            'name'      => $request->name,
            'slug'      => $request->slug,
            'parent_id' => $request->parent_id ?: null,
        ]);

        return redirect()->route('admin.regions.show', $region)
            ->with('success', 'Saqlandi.');
    }

    public function destroy(Region $region): RedirectResponse
    {
        Gate::authorize('admin-panel');

        $region->delete();

        return redirect()->route('admin.regions.index')->with('success', 'O\'chirildi.');
    }
}
