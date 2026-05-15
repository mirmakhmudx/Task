<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use App\Http\Requests\Cabinet\Adverts\AttributesRequest;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Http\Requests\Cabinet\Adverts\PhotosRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdvertService
{


    public function create(int $userId, int $categoryId, ?int $regionId, CreateRequest $request): Advert
    {
        $user     = User::findOrFail($userId);
        $category = Category::findOrFail($categoryId);
        $region   = $regionId ? Region::findOrFail($regionId) : null;

        return DB::transaction(function () use ($request, $user, $category, $region) {
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


    public function addPhotos(int $id, PhotosRequest $request): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($request, $advert) {
            foreach ($request->file('files') as $file) {
                $advert->photos()->create([
                    'file' => $file->store('adverts', 'public'),
                ]);
            }
        });
    }
    public function removePhoto(int $advertId, int $photoId): void
    {
        $advert = $this->getAdvert($advertId);
        $photo = $advert->photos()->findOrFail($photoId);
        Storage::disk('public')->delete($photo->file);
        $photo->delete();
    }

    public function editAttributes(int $id, AttributesRequest $request): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($request, $advert) {
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


    public function sendToModeration(int $id): void
    {
        $this->getAdvert($id)->sendToModeration();
    }

    public function moderate(int $id): void
    {
        $this->getAdvert($id)->moderate(Carbon::now());
    }

    public function reject(int $id, string $reason): void
    {
        $advert = $this->getAdvert($id);
        $advert->reject($reason);
    }


    public function expire(Advert $advert): void
    {
        $advert->expire();
    }

    public function remove(int $id): void
    {
        $advert = $this->getAdvert($id);
        $advert->delete();
    }


    private function getAdvert(int $id): Advert
    {
        return Advert::findOrFail($id);
    }
}
