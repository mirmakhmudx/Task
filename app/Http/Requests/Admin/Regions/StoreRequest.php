<?php

namespace App\Http\Requests\Admin\Regions;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $parentId = $this->input('parent_id') ?: 'NULL';

        return [
            'name'      => ['required', 'string', 'max:255',
                            "unique:regions,name,NULL,id,parent_id,{$parentId}"],
            'slug'      => ['required', 'string', 'max:255',
                            "unique:regions,slug,NULL,id,parent_id,{$parentId}"],
            'parent_id' => ['nullable', 'integer', 'exists:regions,id'],
        ];
    }
}
