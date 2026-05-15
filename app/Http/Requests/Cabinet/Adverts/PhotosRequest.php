<?php

namespace App\Http\Requests\Cabinet\Adverts;

use Illuminate\Foundation\Http\FormRequest;

class PhotosRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'files'   => ['required', 'array', 'min:1'],
            'files.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
