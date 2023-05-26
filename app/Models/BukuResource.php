<?php

namespace App\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray($request){
        return [
            'IdBuku' => $this->IdBuku,
            'JudulBuku' => $this->JudulBuku,
            'Pengarang' => $this->Pengarang,
            'Edisi' => $this->Edisi,
            'ISBN' => $this->ISBN,
            'Penerbit' => $this->Penerbit,
            'TahunTerbit' => $this->TahunTerbit,
            'TempatTerbit' => $this->TempatTerbit,
            'Abstrak' => $this->Abstrak,
            'DeskripsiFisik' => $this->DeskripsiFisik,
            'JumlahEksemplar' => $this->JumlahEksemplar,
            'TanggalMasuk' => $this->TanggalMasuk,
            'CoverBuku' => $this->CoverBuku,
            'TipeKoleksi' => $this->TipeKoleksi,
            'Lokasi' => $this->Lokasi,
            'Bahasa' => $this->Bahasa,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
