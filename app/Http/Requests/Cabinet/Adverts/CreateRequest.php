<?php

namespace App\Http\Requests\Cabinet\Adverts;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['nullable', 'string'],
            'price'        => ['nullable', 'integer', 'min:0'],
            'address'      => ['nullable', 'string', 'max:255'],
            'attributes'   => ['nullable', 'array'],
            'attributes.*' => ['nullable', 'string', 'max:255'],
        ];
    }
}
