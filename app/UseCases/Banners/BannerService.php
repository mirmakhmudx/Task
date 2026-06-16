<?php

namespace App\UseCases\Banners;

use App\Entity\Adverts\Category;
use App\Entity\Banner\Banner;
use App\Entity\Region\Region;
use App\Http\Requests\Cabinet\Banners\CreateRequest;
use App\Http\Requests\Cabinet\Banners\EditRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerService
{
    public function create(int $userId, int $categoryId, ?int $regionId, CreateRequest $request): Banner
    {
        $user     = User::findOrFail($userId);
        $category = Category::findOrFail($categoryId);
        $region   = $regionId ? Region::findOrFail($regionId) : null;

        return DB::transaction(function () use ($request, $user, $category, $region) {
            $path = $request->file('file')->store('banners', 'public');

            $banner = Banner::make([
                'name'   => $request['name'],
                'limit'  => $request['limit'],
                'url'    => $request['url'],
                'format' => $request['format'],
                'file'   => $path,
                'status' => Banner::STATUS_DRAFT,
            ]);

            $banner->user()->associate($user);
            $banner->category()->associate($category);
            $banner->region()->associate($region);
            $banner->saveOrFail();

            return $banner;
        });
    }

    public function edit(int $id, EditRequest $request): void
    {
        $banner = $this->getBanner($id);
        $banner->update([
            'name'   => $request['name'],
            'limit'  => $request['limit'],
            'url'    => $request['url'],
            'format' => $request['format'],
        ]);
    }

    public function changeFile(int $id, UploadedFile $file): void
    {
        $banner = $this->getBanner($id);
        if ($banner->file) {
            Storage::disk('public')->delete($banner->file);
        }
        $banner->changeFile($file->store('banners', 'public'));
    }

    public function sendToModeration(int $id): void
    {
        $this->getBanner($id)->sendToModeration();
    }

    public function moderate(int $id): void
    {
        $this->getBanner($id)->moderate();
    }

    public function reject(int $id, string $reason): void
    {
        $this->getBanner($id)->reject($reason);
    }

    public function pay(int $id): void
    {
        $this->getBanner($id)->pay(Carbon::now());
    }

    public function remove(int $id): void
    {
        $banner = $this->getBanner($id);
        if ($banner->file) {
            Storage::disk('public')->delete($banner->file);
        }
        $banner->delete();
    }

    private function getBanner(int $id): Banner
    {
        return Banner::findOrFail($id);
    }
}
