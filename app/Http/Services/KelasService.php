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

    public function GetKelasByName($tenant, $grade, $rombel, $id = 0){
        $getData = DB::table('Master.Kelas')
                    ->where([
                        ['Tenant', $tenant],
                        ['Grade',$grade],
                        ['Rombel',$rombel],
                    ]);

        if($id != 0){
            $getData->where('IdKelas', '!=', $id);
        }

        return $getData->first();
    }

    public function CreateKelas($tenant, $request){
        $checkDuplicate = $this->CheckDuplicateCreate($tenant, $request);

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

    public function CheckDuplicateCreate($tenant, $request){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $getKelas = $this->GetKelasByName($tenant, $request->get('Grade'), $request->get('Rombel'));

        if($getKelas != null){
            $returnres->statusres = false;
            $returnres->msg = "Data duplicate";

            return $returnres;
        }

        return $returnres;
    }

    public function UpdateKelas($tenant, $id, $request){
        $checkDuplicate = $this->CheckDuplicateUpdate($tenant, $request, $id);

        if($checkDuplicate->statusres != true){
            return $checkDuplicate;
        }

        DB::table('Master.Kelas')
            ->where('IdKelas', $id)
            ->update([
                'Grade' => $request->get('Grade'),
                'Rombel' => $request->get('Rombel')
            ]);

        return $this->GetKelasById($tenant, $id);
    }

    public function CheckDuplicateUpdate($tenant, $request, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $getKelas = $this->GetKelasById($tenant, $id);
        if($getKelas == null){
            $returnres->statusres = false;
            $returnres->msg = "Data not Found";

            return $returnres;
        }

        $getDuplicateData = $this->GetKelasByName($tenant, $request->get('Grade'), $request->get('Rombel'), $id);

        if($getDuplicateData != null){
            $returnres->statusres = false;
            $returnres->msg = "Data duplicate";

            return $returnres;
        }

        $returnres->data = $getKelas;
        return $returnres;
    }

    public function delete($tenant, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $deleteData = DB::table('Master.Kelas')
                        ->where([
                            ['Tenant', $tenant],
                            ['IdKelas',$id]
                        ])
                        ->delete();

        if($deleteData == 0){
            $returnres->statusres = false;
            $returnres->msg = "Data not found";
        }

        return $returnres;
    }

}
