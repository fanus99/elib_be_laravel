<?php

namespace App\DTOs;

final class PeminjamanDTo extends FormRequest
{
    public function rules()
    {
        return [
            'TanggalPinjam' => [
                'required',
            ],
            'BatasPengembalian' => [
                'required',
            ],
            'Siswa' => [
                'required',
            ],
            'Buku' => [
                'required',
            ],
        ];
    }
}
