<?php

namespace App\DTOs;

final class RefreshTokenDTo extends FormRequest
{
    public function rules()
    {
        return [
            'access_token' => [
                'required',
            ],
            'refresh_token' => [
                'required',
            ],
        ];
    }
}
