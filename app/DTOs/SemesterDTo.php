<?php

namespace App\DTOs;

final class SemesterDTo extends FormRequest
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
