<?php

namespace App\Http\Requests\Cabinet\Banners;

use App\Entity\Banner\Banner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string|max:255',
            'limit'  => 'required|integer|min:1',
            'url'    => 'required|url',
            'format' => ['required', Rule::in(array_keys(Banner::formatsList()))],
        ];
    }
}
