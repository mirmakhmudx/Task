<?php

namespace App\Http\Requests\Admin\Adverts;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255',
                       'unique:advert_categories,slug,' . $category->id],
        ];
    }
}
