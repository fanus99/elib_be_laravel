<?php

namespace App\DTOs;

final class KelasDTo extends FormRequest
{
    public function rules()
    {
        return [
            'TahunAjaran' => [
                'required',
            ],
            'Semester' => [
                'required',
            ]
        ];
    }
}
