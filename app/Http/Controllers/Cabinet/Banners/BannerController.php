<?php

namespace App\Http\Controllers\Cabinet\Banners;

use App\Entity\Banner\Banner;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Banners\EditRequest;
use App\UseCases\Banners\BannerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function __construct(
        private readonly BannerService $service
    ) {}

    public function index(): View
    {
        $banners = Banner::forUser(Auth::user())
            ->with(['category', 'region'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('cabinet.banners.index', compact('banners'));
    }

    public function show(Banner $banner): View
    {
        $this->checkAccess($banner);
        return view('cabinet.banners.show', compact('banner'));
    }

    // Edit forma
    public function editForm(Banner $banner): View
    {
        $this->checkAccess($banner);
        $formats = Banner::formatsList();
        return view('cabinet.banners.edit', compact('banner', 'formats'));
    }

    // Edit saqlash
    public function update(EditRequest $request, Banner $banner): RedirectResponse
    {
        $this->checkAccess($banner);
        $this->service->edit($banner->id, $request);
        return redirect()->route('cabinet.banners.show', $banner)->with('success', 'Saqlandi.');
    }

    // Change File forma
    public function fileForm(Banner $banner): View
    {
        $this->checkAccess($banner);
        return view('cabinet.banners.file', compact('banner'));
    }

    // Change File saqlash
    public function file(Request $request, Banner $banner): RedirectResponse
    {
        $this->checkAccess($banner);
        $request->validate(['file' => 'required|image|mimes:jpg,jpeg,png,gif|max:5120']);
        $this->service->changeFile($banner->id, $request->file('file'));
        return redirect()->route('cabinet.banners.show', $banner)->with('success', 'Rasm almashtirildi.');
    }

    // Moderatsiyaga yuborish
    public function sendToModeration(Banner $banner): RedirectResponse
    {
        $this->checkAccess($banner);
        try {
            $this->service->sendToModeration($banner->id);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
        return redirect()->route('cabinet.banners.show', $banner);
    }

    // O'chirish
    public function destroy(Banner $banner): RedirectResponse
    {
        $this->checkAccess($banner);
        $this->service->remove($banner->id);
        return redirect()->route('cabinet.banners.index')->with('success', 'O\'chirildi.');
    }

    private function checkAccess(Banner $banner): void
    {
        if (!$banner->isOwnedBy(Auth::user())) {
            abort(403);
        }
    }
}
