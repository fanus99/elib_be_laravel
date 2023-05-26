<?php

namespace App\DTOs;

final class BukuDTo extends FormRequest
{
    public function rules()
    {
        return [
            'JudulBuku' => [
                'required',
            ],
            'Pengarang' => [
                'required',
            ],
            'Edisi' => [
                'required',
            ],
            'ISBN' => [
                'required',
            ],
            'Penerbit' => [
                'required',
            ],
            'TahunTerbit' => [
                'required',
            ],
            'TempatTerbit' => [
                'required',
            ],
            'Abstrak' => [
                'required',
            ],
            'DeskripsiFisik' => [
                'required',
            ],
            'JumlahEksemplar' => [
                'required',
            ],
            'TanggalMasuk' => [
                'required',
            ],
            'CoverBuku' => [
                'required',
            ],
            'TipeKoleksi' => [
                'required',
            ],
            'Lokasi' => [
                'required',
            ],
            'bahasa' => [
                'required',
            ],
        ];
    }
}

