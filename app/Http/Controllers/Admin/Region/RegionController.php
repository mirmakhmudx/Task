<?php

namespace App\Http\Controllers\Admin\Region;

use App\Entity\Region\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Regions\StoreRequest;
use App\Http\Requests\Admin\Regions\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function index(): View
    {
        Gate::authorize('admin-panel');

        $regions = Region::with('parent')
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        return view('admin.regions.index', compact('regions'));
    }

    public function create(): View
    {
        Gate::authorize('admin-panel');

        $parents = Region::whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('admin.regions.create', compact('parents'));
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Gate::authorize('admin-panel');

        $max = Region::max('sort_order') ?? 0;

        $region = Region::create([
            'name'       => $request->name,
            'slug'       => $request->slug,
            'parent_id'  => $request->parent_id ?: null,
            'sort_order' => $max + 1,
        ]);

        return redirect()->route('admin.regions.show', $region)
            ->with('success', 'Region yaratildi.');
    }

    public function show(Region $region): View
    {
        Gate::authorize('admin-panel');

        $children = $region->children()->orderBy('sort_order')->get();

        return view('admin.regions.show', compact('region', 'children'));
    }

    public function edit(Region $region): View
    {
        Gate::authorize('admin-panel');

        $parents = Region::whereNull('parent_id')
            ->where('id', '!=', $region->id)
            ->orderBy('sort_order')
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

        return redirect()->route('admin.regions.index')
            ->with('success', 'O\'chirildi.');
    }

    // ==================== TARTIB ====================

    public function first(Region $region): RedirectResponse
    {
        Gate::authorize('admin-panel');

        DB::transaction(function () use ($region) {
            // Barcha regionlarni sort_order bo'yicha olamiz
            $siblings = Region::where('parent_id', $region->parent_id)
                ->orderBy('sort_order')
                ->get();

            // Ushbu region birinchi bo'lsin
            $order = 1;
            foreach ($siblings as $sibling) {
                if ($sibling->id === $region->id) {
                    continue;
                }
                $sibling->update(['sort_order' => $order + 1]);
                $order++;
            }
            $region->update(['sort_order' => 1]);
        });

        return redirect()->route('admin.regions.index');
    }

    public function up(Region $region): RedirectResponse
    {
        Gate::authorize('admin-panel');

        DB::transaction(function () use ($region) {
            $prev = Region::where('parent_id', $region->parent_id)
                ->where('sort_order', '<', $region->sort_order)
                ->orderByDesc('sort_order')
                ->first();

            if ($prev) {
                $currentOrder = $region->sort_order;
                $region->update(['sort_order' => $prev->sort_order]);
                $prev->update(['sort_order' => $currentOrder]);
            }
        });

        return redirect()->route('admin.regions.index');
    }

    public function down(Region $region): RedirectResponse
    {
        Gate::authorize('admin-panel');

        DB::transaction(function () use ($region) {
            $next = Region::where('parent_id', $region->parent_id)
                ->where('sort_order', '>', $region->sort_order)
                ->orderBy('sort_order')
                ->first();

            if ($next) {
                $currentOrder = $region->sort_order;
                $region->update(['sort_order' => $next->sort_order]);
                $next->update(['sort_order' => $currentOrder]);
            }
        });

        return redirect()->route('admin.regions.index');
    }

    public function last(Region $region): RedirectResponse
    {
        Gate::authorize('admin-panel');

        DB::transaction(function () use ($region) {
            $siblings = Region::where('parent_id', $region->parent_id)
                ->orderBy('sort_order')
                ->get();

            $order = 1;
            foreach ($siblings as $sibling) {
                if ($sibling->id === $region->id) {
                    continue;
                }
                $sibling->update(['sort_order' => $order]);
                $order++;
            }
            $region->update(['sort_order' => $order]);
        });

        return redirect()->route('admin.regions.index');
    }
}
