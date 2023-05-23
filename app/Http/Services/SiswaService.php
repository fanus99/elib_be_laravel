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

    public function GetSiswaByNIS($tenant, $nis){
        return DB::table('Master.Siswa')
                ->where([
                    ['Tenant', $tenant],
                    ['NIS',$nis],
                ])
                ->first();
    }

    public function CreateSiswa($tenant, $request){
        $checkDuplicate = $this->CheckDuplicate($tenant, $request);

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

    public function UpdateSiswa($tenant, $id, $request){
        $checkDuplicate = $this->CheckDuplicate($tenant, $request, $id);

        if($checkDuplicate->statusres != true){
            return $checkDuplicate;
        }

        DB::table('Master.Siswa')
              ->where('IdSiswa', $id)
              ->update([
                ['Nama' => $request->get('Nama')],
                ['NIS' => $request->get('NIS')]
            ]);

        return $this->GetSiswaById($tenant, $id);
    }

    public function CheckDuplicate($tenant, $request, $id = null){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $getSiswa = $this->GetSiswaByNIS($tenant, $request->get('NIS'));

        if($id != null){
            if($getSiswa == null){
                $returnres->statusres = false;
                $returnres->msg = "Data not Found";
            }else{
                if($getSiswa->IdSiswa != $id){
                    $returnres->statusres = false;
                    $returnres->msg = "Data duplicate";
                }
            }

            return $returnres;
        }

        if($getSiswa != null){
            $returnres->statusres = false;
            $returnres->msg = "Data duplicate";

            return $returnres;
        }

        return $returnres;
    }

    public function delete($tenant, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $deleteData = DB::table('Master.Siswa')
                        ->where([
                            ['Tenant', $tenant],
                            ['IdSiswa',$id]])
                        ->delete();

        if($deleteData == 0){
            $returnres->statusres = false;
            $returnres->msg = "Data not found";
        }

        return $returnres;
    }

}
