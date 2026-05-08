<?php

namespace App\Http\Requests\Admin\Adverts;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'max:255'],
            'slug'   => ['required', 'string', 'max:255', 'unique:advert_categories,slug'],
            'parent' => ['nullable', 'integer', 'exists:advert_categories,id'],
        ];
    }
}
