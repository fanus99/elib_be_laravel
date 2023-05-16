<?php

namespace App\DTOs;

final class RegisterDTo extends FormRequest
{
    public function rules()
    {
        return [
            'InstitutionName' => [
                'required',
            ],
            'Username' => [
                'required',
            ],
            'Password' => [
                'required',
            ],
            'FullName' => [
                'required',
            ],
            'Email' => [
                'required','email'
            ],
            'Phonenumber'=> [
                'required','numeric','digits_between:11,14'
            ],
        ];
    }
}
