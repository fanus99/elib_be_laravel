<?php

namespace App\Http\Services;
use DB;
use Illuminate\Support\Facades\Hash;
use App\DTOs\SiswaDTo;
use App\Http\Services\TenantService;
use App\Models\UniversalResponse;
use Carbon;

class SiswaService
{
    public function GetAll($tenant)
    {
        return DB::table('Master.Siswa')
                ->where('Tenant', $tenant)
                ->get();
    }

    public function GetSiswaById($tenant, $id)
    {
        return DB::table('Master.Siswa')
                ->where([
                    ['Tenant', $tenant],
                    ['IdSiswa',$id]])
                ->first();
    }

    public function GetSiswaByNIS($tenant, $nis, $id = 0){
        $getData = DB::table('Master.Siswa')
                    ->where([
                        ['Tenant', $tenant],
                        ['NIS',$nis],
                    ]);

        if($id != 0){
            $getData->where('IdSiswa', '!=', $id);
        }

        return $getData->first();
    }

    public function CreateSiswa($tenant, $request){
        $checkDuplicate = $this->CheckDuplicateCreate($tenant, $request);

        if($checkDuplicate->statusres != true){
            return $checkDuplicate;
        }

        $lastInsertId = DB::table('Master.Siswa')->insertGetId([
            'Nama' => $request->get('Nama'),
            'NIS' => $request->get('NIS'),
            'Tenant' => $tenant,
        ], 'IdSiswa');

        return $this->GetSiswaById($tenant, $lastInsertId);
    }

    public function CheckDuplicateCreate($tenant, $request){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $getSiswa = $this->GetSiswaByNIS($tenant, $request->get('NIS'));

        if($getSiswa != null){
            $returnres->statusres = false;
            $returnres->msg = "Data duplicate";

            return $returnres;
        }

        return $returnres;
    }

    public function UpdateSiswa($tenant, $id, $request){
        $checkDuplicate = $this->CheckDuplicateUpdate($tenant, $request, $id);

        if($checkDuplicate->statusres != true){
            return $checkDuplicate;
        }

        DB::table('Master.Siswa')
            ->where('IdSiswa', $id)
            ->update([
                'Nama' => $request->get('Nama'),
                'NIS' => $request->get('NIS')
            ]);

        $checkDuplicate->data->Nama = $request->get('Nama');
        $checkDuplicate->data->Rombel = $request->get('NIS');

        return $checkDuplicate->data;
    }

    public function CheckDuplicateUpdate($tenant, $request, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $getSiswa = $this->GetSiswaById($tenant, $id);
        if($getSiswa == null){
            $returnres->statusres = false;
            $returnres->msg = "Data not Found";

            return $returnres;
        }

        $getDuplicateData = $this->GetSiswaByNIS($tenant, $request->get('NIS'), $id);

        if($getDuplicateData != null){
            $returnres->statusres = false;
            $returnres->msg = "Data duplicate";

            return $returnres;
        }

        $returnres->data = $getSiswa;
        return $returnres;
    }

    public function delete($tenant, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $deleteData = DB::table('Master.Siswa')
                        ->where([
                            ['Tenant', $tenant],
                            ['IdSiswa',$id]
                        ])
                        ->delete();

        if($deleteData == 0){
            $returnres->statusres = false;
            $returnres->msg = "Data not found";
        }

        return $returnres;
    }

}
