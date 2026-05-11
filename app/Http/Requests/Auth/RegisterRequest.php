<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }



    public function messages(): array
    {
        return [
            'name.required'      => 'Ism kiritish majburiy.',
            'name.min'           => 'Ism kamida 2 ta belgidan iborat bo\'lishi kerak.',
            'email.required'     => 'Email kiritish majburiy.',
            'email.email'        => 'Email formati noto\'g\'ri.',
            'email.unique'       => 'Bu email allaqachon ro\'yxatdan o\'tgan.',
            'password.required'  => 'Parol kiritish majburiy.',
            'password.min'       => 'Parol kamida 8 ta belgidan iborat bo\'lishi kerak.',
            'password.confirmed' => 'Parollar mos kelmadi.',
        ];
    }
}
