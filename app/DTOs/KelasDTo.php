<?php

namespace App\DTOs;

final class KelasDTo extends FormRequest
{
    public function rules()
    {
        return [
            'Grade' => [
                'required',
            ],
            'Rombel' => [
                'required',
            ]
        ];
    }
}
