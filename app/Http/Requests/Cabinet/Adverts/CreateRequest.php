<?php

namespace App\Http\Requests\Cabinet\Adverts;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:advert_categories,id'],
            'region_id'   => ['nullable', 'integer', 'exists:regions,id'],
            'title'       => ['required', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'price'       => ['nullable', 'integer', 'min:0'],
            'address'     => ['nullable', 'string', 'max:255'],
        ];
    }
}
