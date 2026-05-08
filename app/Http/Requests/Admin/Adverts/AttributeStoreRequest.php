<?php

namespace App\Http\Requests\Admin\Adverts;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Entity\Adverts\Attribute;
use Illuminate\Validation\Rule;

class AttributeStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'type'     => ['required', 'string', Rule::in(array_keys(Attribute::typesList()))],
            'required' => ['nullable', 'boolean'],
            'default'  => ['nullable', 'string', 'max:255'],
            'variants' => ['nullable', 'string'],
            'sort'     => ['required', 'integer', 'min:0'],
        ];
    }
}
