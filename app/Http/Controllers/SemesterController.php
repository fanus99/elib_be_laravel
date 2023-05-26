<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\DTOs\SemesterDTo;
use App\Http\Services\SemesterService;
use App\Models\SemesterResource;

class SemesterController extends BaseController
{
    private $getuser;
    private $SemesterService;


    public function __construct(SemesterService $SemesterService, Request $request)
    {
        $this->getuser = $request->auth;
        $this->SemesterService = $SemesterService;
    }

    public function GetAll()
    {
        $data = $this->SemesterService->GetAll($this->getuser->Tenant);
        return $this->ApiSuccessResponseGet(SemesterResource::collection($data));
    }

    public function GetById($id)
    {
        $data = $this->SemesterService->GetSemesterById($this->getuser->Tenant, $id);
        return $this->ApiSuccessResponseGetFirst(new SemesterResource($data));
    }

    public function create(SemesterDTo $request){
        $data = $this->SemesterService->CreateSemester($this->getuser->Tenant, $request);
        return $this->ApiPostResponse($data, "Data Created");
    }

    public function update($id, SemesterDTo $request){
        $data = $this->SemesterService->UpdateSemester($this->getuser->Tenant, $id, $request);
        return $this->ApiPostResponse($data, "Data Updated");
    }

    public function delete($id){
        $data = $this->SemesterService->delete($this->getuser->Tenant, $id);
        return $this->ApiPostResponse($data, "Data Deleted");
    }

    public function checkSemesterActive(){
        $data = $this->SemesterService->checkSemesterActive($this->getuser->Tenant);
        return $this->ApiSuccessResponseGetFirst(new SemesterResource($data));
    }

    public function setSemesterAktif($id){
        $data = $this->SemesterService->setSemesterActive($this->getuser->Tenant, $id);
        return $this->ApiPostResponse($data, "Data Updated");
        // return response()->json($data);
    }
}
