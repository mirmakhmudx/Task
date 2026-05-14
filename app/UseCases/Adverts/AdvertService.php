<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Http\Requests\Cabinet\Adverts\AttributesRequest;
use App\Http\Requests\Cabinet\Adverts\PhotosRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdvertService
{
    public function create(int $userId, int $categoryId, ?int $regionId, CreateRequest $request): Advert
    {
        /** @var User $user */
        $user = User::findOrFail($userId);
        /** @var Category $category */
        $category = Category::findOrFail($categoryId);
        $region = $regionId ? Region::findOrFail($regionId) : null;

        return DB::transaction(function () use ($user, $category, $region, $request) {
            $advert = Advert::make([
                'title'   => $request->title,
                'content' => $request->content,
                'price'   => $request->price,
                'address' => $request->address,
                'status'  => Advert::STATUS_DRAFT,
            ]);

            $advert->user()->associate($user);
            $advert->category()->associate($category);
            $advert->region()->associate($region);
            $advert->saveOrFail();

            foreach ($category->allAttributes() as $attribute) {
                $value = $request->attributes[$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value'        => $value,
                    ]);
                }
            }

            return $advert;
        });
    }


    public function editAttributes(int $id, AttributesRequest $request): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($advert, $request) {
            $advert->values()->delete();

            foreach ($advert->category->allAttributes() as $attribute) {
                $value = $request->attributes[$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value'        => $value,
                    ]);
                }
            }
        });
    }


    public function addPhotos(int $id, PhotosRequest $request): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($advert, $request) {
            foreach ($request->file('files') as $file) {
                $advert->photos()->create([
                    'file' => $file->store('adverts', 'public'),
                ]);
            }
        });
    }

    public function removePhoto(int $id, int $photoId): void
    {
        $advert = $this->getAdvert($id);
        $photo  = $advert->photos()->findOrFail($photoId);

        \Storage::disk('public')->delete($photo->file);
        $photo->delete();
    }

    // ==================== Moderation ====================

    public function sendToModeration(int $id): void
    {
        $advert = $this->getAdvert($id);
        $advert->sendToModeration();
    }

    public function moderate(int $id): void
    {
        $advert = $this->getAdvert($id);
        $advert->moderate(Carbon::now());
    }

    public function reject(int $id, string $reason): void
    {
        $advert = $this->getAdvert($id);
        $advert->reject($reason);
    }

    // ==================== Remove ====================

    public function remove(int $id): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($advert) {
            foreach ($advert->photos as $photo) {
                \Storage::disk('public')->delete($photo->file);
            }
            $advert->delete();
        });
    }

    // ==================== Private ====================

    private function getAdvert(int $id): Advert
    {
        return Advert::findOrFail($id);
    }
}
