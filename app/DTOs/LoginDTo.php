<?php

namespace App\DTOs;

final class LoginDTo extends FormRequest
{
    public function rules()
    {
        return [
            'Username' => [
                'required',
            ],
            'Password' => [
                'required',
            ],
        ];
    }
}
