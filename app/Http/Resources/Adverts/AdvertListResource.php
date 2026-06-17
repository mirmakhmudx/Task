<?php

namespace App\Http\Resources\Adverts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AdvertListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'title'   => $this->title,
            'price'   => $this->price,
            'address' => $this->address,
            'photo'   => $this->photos->first() ? Storage::url($this->photos->first()->file) : null,
            'category' => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ],
            'region' => $this->region ? [
                'id'   => $this->region->id,
                'name' => $this->region->name,
            ] : null,
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
