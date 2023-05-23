<?php

namespace App\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class SemesterResource extends JsonResource
{
    public function toArray($request){
        return [
            'IdSemester' => $this->IdSemester,
            'TahunAjaran' => $this->TahunAjaran,
            'Semester' => $this->Semester,
            'IsActive' => $this->IsActive,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
