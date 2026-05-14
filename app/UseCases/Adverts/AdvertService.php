<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use App\Models\User;

class AdvertService
{
    public function create(
        int $userId,
        int $categoryId,
        ?int $regionId,
        CreateRequest $request
    ): Advert {
        /** @var User $user */
        $user = User::findOrFail($userId);

        /** @var Category $category */
        $category = Category::findOrFail($categoryId);

        /** @var Region|null $region */
        $region = $regionId ? Region::findOrFail($regionId) : null;

        /** @var Advert $advert */
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
    }
}
