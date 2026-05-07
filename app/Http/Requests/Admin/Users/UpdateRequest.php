<?php

namespace App\Http\Requests\Admin\Users;

use App\Entity\User\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'min:2', 'max:100'],
            'email'  => ['required', 'email', 'unique:users,email,' . $this->route('user')->id],
            'role'   => ['required', 'in:' . implode(',', array_keys(User::rolesList()))],
            'status' => ['required', 'in:active,waiting'],
        ];
    }
}
