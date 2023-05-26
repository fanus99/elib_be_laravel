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

    public function GetSemesterByName($tenant, $tahunajaran, $semester, $id = 0){
        $getData = DB::table('Master.Semester')
                    ->where([
                        ['Tenant', $tenant],
                        ['TahunAjaran',$tahunajaran],
                        ['Semester',$semester],
                    ]);

        if($id != 0){
            $getData->where('IdSemester', '!=', $id);
        }

        return $getData->first();
    }

    public function CreateSemester($tenant, $request){
        $checkDuplicate = $this->CheckDuplicateCreate($tenant, $request);

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

    public function CheckDuplicateCreate($tenant, $request){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $getSemester = $this->GetSemesterByName($tenant, $request->get('TahunAjaran'), $request->get('Semester'));

        if($getSemester != null){
            $returnres->statusres = false;
            $returnres->msg = "Data duplicate";

            return $returnres;
        }

        return $returnres;
    }

    public function UpdateSemester($tenant, $id, $request){
        $checkDuplicate = $this->CheckDuplicateUpdate($tenant, $request, $id);

        if($checkDuplicate->statusres != true){
            return $checkDuplicate;
        }

        DB::table('Master.Semester')
            ->where('IdSemester', $id)
            ->update([
                'TahunAjaran' => $request->get('TahunAjaran'),
                'Semester' => $request->get('Semester')
            ]);

        return $this->GetSemesterById($tenant, $id);
    }

    public function CheckDuplicateUpdate($tenant, $request, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $getSemester = $this->GetSemesterById($tenant, $id);
        if($getSemester == null){
            $returnres->statusres = false;
            $returnres->msg = "Data not Found";

            return $returnres;
        }

        $getDuplicateData = $this->GetSemesterByName($tenant, $request->get('TahunAjaran'), $request->get('Semester'), $id);

        if($getDuplicateData != null){
            $returnres->statusres = false;
            $returnres->msg = "Data duplicate";

            return $returnres;
        }

        $returnres->data = $getSemester;
        return $returnres;
    }

    public function delete($tenant, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $deleteData = DB::table('Master.Semester')
                        ->where([
                            ['Tenant', $tenant],
                            ['IdSemester',$id]
                        ])
                        ->delete();

        if($deleteData == 0){
            $returnres->statusres = false;
            $returnres->msg = "Data not found";
        }

        return $returnres;
    }

    public function checkSemesterActive($tenant){
        return DB::table('Master.Semester')
                ->where([['Tenant', $tenant], ['IsActive', true]])
                ->first();
    }

    public function setSemesterActive($tenant,$id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        DB::table('Master.Semester')
            ->where([['IdSemester', '!=',$id],['Tenant', $tenant]])
            ->update([
                'IsActive' => false,
            ]);

        $setActive  = DB::table('Master.Semester')
            ->where([['IdSemester', $id],['Tenant', $tenant]])
            ->update([
                'IsActive' => true,
            ]);

        if($setActive == 0){
            $returnres->statusres = false;
            $returnres->msg = "Data not found";
            return $returnres;
        }

        return $this->checkSemesterActive($tenant);
    }

}
