<?php

namespace App\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class KelasResource extends JsonResource
{
    public function toArray($request){
        return [
            'IdKelas' => $this->IdKelas,
            'Grade' => $this->Grade,
            'Rombel' => $this->Rombel,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
