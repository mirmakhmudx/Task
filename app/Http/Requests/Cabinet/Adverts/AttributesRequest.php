<?php

namespace App\Http\Requests\Cabinet\Adverts;

use App\Entity\Adverts\Advert;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributesRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        /** @var Advert $advert */
        $advert = $this->route('advert');
        $items  = [];

        foreach ($advert->category->allAttributes() as $attribute) {
            $rules = [$attribute->required ? 'required' : 'nullable'];

            if ($attribute->isInteger()) {
                $rules[] = 'integer';
            } elseif ($attribute->isFloat()) {
                $rules[] = 'numeric';
            } else {
                $rules[] = 'string';
                $rules[] = 'max:255';
            }

            if ($attribute->isSelect()) {
                $rules[] = Rule::in($attribute->variants);
            }

            $items['attributes.' . $attribute->id] = $rules;
        }

        return array_merge([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'price'   => ['nullable', 'integer', 'min:0'],
        ], $items);
    }
}
