<?php

namespace App\Http\Services;
use DB;
use Illuminate\Support\Facades\Hash;
use App\DTOs\KelasDTo;
use App\Http\Services\TenantService;
use App\Models\UniversalResponse;
use Carbon;

class KelasService
{
    public function GetAll($tenant)
    {
        return DB::table('Master.Kelas')
                ->where('Tenant', $tenant)
                ->get();
    }

    public function GetKelasById($tenant, $id)
    {
        return DB::table('Master.Kelas')
                ->where([
                    ['Tenant', $tenant],
                    ['IdKelas',$id]])
                ->first();
    }

    public function GetKelasByName($tenant, $grade, $rombel){
        return DB::table('Master.Kelas')
                ->where([
                    ['Tenant', $tenant],
                    ['Grade',$grade],
                    ['Rombel',$rombel],
                ])
                ->first();
    }

    public function CreateKelas($tenant, $request){
        $checkDuplicate = $this->CheckDuplicate($tenant, $request);

        if($checkDuplicate->statusres != true){
            return $checkDuplicate;
        }

        $lastInsertId = DB::table('Master.Kelas')->insertGetId([
            'Grade' => $request->get('Grade'),
            'Rombel' => $request->get('Rombel'),
            'Tenant' => $tenant,
        ], 'IdKelas');

        return $this->GetKelasById($tenant, $lastInsertId);
    }

    public function UpdateKelas($tenant, $id, $request){
        $checkDuplicate = $this->CheckDuplicate($tenant, $request, $id);

        if($checkDuplicate->statusres != true){
            return $checkDuplicate;
        }

        DB::table('Master.Kelas')
              ->where('IdKelas', $id)
              ->update([
                ['Grade' => $request->get('Grade')],
                ['Rombel' => $request->get('Rombel')]
            ]);

        return $this->GetKelasById($tenant, $id);
    }

    public function CheckDuplicate($tenant, $request, $id = null){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $getKelas = $this->GetKelasByName($tenant, $request->get('Grade'), $request->get('Rombel'));

        if($id != null){
            if($getKelas == null){
                $returnres->statusres = false;
                $returnres->msg = "Data not Found";
            }else{
                if($getKelas->IdKelas != $id){
                    $returnres->statusres = false;
                    $returnres->msg = "Data duplicate";
                }
            }

            return $returnres;
        }

        if($getKelas != null){
            $returnres->statusres = false;
            $returnres->msg = "Data duplicate";

            return $returnres;
        }

        return $returnres;
    }

    public function delete($tenant, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $deleteData = DB::table('Master.Kelas')
                        ->where([
                            ['Tenant', $tenant],
                            ['IdKelas',$id]])
                        ->delete();

        if($deleteData == 0){
            $returnres->statusres = false;
            $returnres->msg = "Data not found";
        }

        return $returnres;
    }

}