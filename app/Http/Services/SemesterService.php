<?php

namespace App\Http\Services;
use DB;
use Illuminate\Support\Facades\Hash;
use App\DTOs\SemesterDTo;
use App\Http\Services\TenantService;
use App\Models\UniversalResponse;
use Carbon;

class SemesterService
{
    public function GetAll($tenant)
    {
        return DB::table('Master.Semester')
                ->where('Tenant', $tenant)
                ->get();
    }

    public function GetSemesterById($tenant, $id)
    {
        return DB::table('Master.Semester')
                ->where([
                    ['Tenant', $tenant],
                    ['IdSemester',$id]])
                ->first();
    }

    public function GetSemesterByName($tahunajaran, $semester, $rombel){
        return DB::table('Master.Semester')
                ->where([
                    ['Tenant', $tenant],
                    ['TahunAjaran',$grade],
                    ['Semester',$rombel],
                ])
                ->first();
    }

    public function CreateSemester($tenant, $request){
        $checkDuplicate = $this->CheckDuplicate($tenant, $request);

        if($checkDuplicate->statusres != true){
            return $checkDuplicate;
        }

        $lastInsertId = DB::table('Master.Semester')->insertGetId([
            'TahunAjaran' => $request->get('TahunAjaran'),
            'Semester' => $request->get('Semester'),
            'Tenant' => $tenant,
        ], 'IdSemester');

        return $this->GetSemesterById($tenant, $lastInsertId);
    }

    public function UpdateSemester($tenant, $id, $request){
        $checkDuplicate = $this->CheckDuplicate($tenant, $request, $id);

        if($checkDuplicate->statusres != true){
            return $checkDuplicate;
        }

        DB::table('Master.Semester')
              ->where('IdSemester', $id)
              ->update([
                ['TahunAjaran' => $request->get('TahunAjaran')],
                ['Semester' => $request->get('Semester')]
            ]);

        return $this->GetSemesterById($tenant, $id);
    }

    public function CheckDuplicate($tenant, $request, $id = null){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $getSemester = $this->GetSemesterByName($tenant, $request->get('TahunAjaran'), $request->get('Semester'));

        if($id != null){
            if($getSemester == null){
                $returnres->statusres = false;
                $returnres->msg = "Data not Found";
            }else{
                if($getSemester->IdSemester != $id){
                    $returnres->statusres = false;
                    $returnres->msg = "Data duplicate";
                }
            }

            return $returnres;
        }

        if($getSemester != null){
            $returnres->statusres = false;
            $returnres->msg = "Data duplicate";

            return $returnres;
        }

        return $returnres;
    }

    public function delete($tenant, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $deleteData = DB::table('Master.Semester')
                        ->where([
                            ['Tenant', $tenant],
                            ['IdSemester',$id]])
                        ->delete();

        if($deleteData == 0){
            $returnres->statusres = false;
            $returnres->msg = "Data not found";
        }

        return $returnres;
    }

}
