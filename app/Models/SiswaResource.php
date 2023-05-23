<?php

namespace App\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class SiswaResource extends JsonResource
{
    public function toArray($request){
        return [
            'IdSiswa' => $this->IdSiswa,
            'Nama' => $this->Nama,
            'NIS' => $this->NIS,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
