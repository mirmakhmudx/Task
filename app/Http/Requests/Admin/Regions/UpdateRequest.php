<?php

namespace App\Http\Requests\Admin\Regions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $region   = $this->route('region');
        $parentId = $region->parent_id ?: 'NULL';

        return [
            'name' => ['required', 'string', 'max:255',
                       "unique:regions,name,{$region->id},id,parent_id,{$parentId}"],
            'slug' => ['required', 'string', 'max:255',
                       "unique:regions,slug,{$region->id},id,parent_id,{$parentId}"],
        ];
    }
}
