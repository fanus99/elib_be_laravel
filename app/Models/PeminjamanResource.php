<?php

namespace App\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanResource extends JsonResource
{
    public function toArray($request){
        return [
            'IdPeminjaman' => $this->IdPeminjaman,
            'TanggalPinjam' => $this->TanggalPinjam,
            'BatasPengembalian' => $this->BatasPengembalian,
            'TanggalPengembalian' => $this->TanggalPengembalian,
            'Siswa' => $this->Siswa,
            'Buku' => $this-> Buku,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
