<?php

namespace App\DTOs;

final class SiswaDTo extends FormRequest
{
    public function rules()
    {
        return [
            'Nama' => [
                'required',
            ],
            'NIS' => [
                'required',
            ]
        ];
    }
}
