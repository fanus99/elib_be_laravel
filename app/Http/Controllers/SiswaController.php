<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\DTOs\SiswaDTo;
use App\Http\Services\SiswaService;
use App\Models\SiswaResource;

class SiswaController extends BaseController
{
    private $getuser;
    private $SiswaService;


    public function __construct(SiswaService $SiswaService, Request $request)
    {
        $this->getuser = $request->auth;
        $this->SiswaService = $SiswaService;
    }

    public function GetAll()
    {
        $data = $this->SiswaService->GetAll($this->getuser->Tenant);
        return $this->ApiSuccessResponseGet(SiswaResource::collection($data));
    }

    public function GetById($id)
    {
        $data = $this->SiswaService->GetSiswaById($this->getuser->Tenant, $id);
        return $this->ApiSuccessResponseGetFirst(new SiswaResource($data));
    }

    public function create(SiswaDTo $request){
        $data = $this->SiswaService->CreateSiswa($this->getuser->Tenant, $request);
        return $this->ApiPostResponse($data, "Data Created");
    }

    public function update($id, SiswaDTo $request){
        $data = $this->SiswaService->UpdateSiswa($this->getuser->Tenant, $id, $request);
        return $this->ApiPostResponse($data, "Data Updated");
    }

    public function delete($id){
        $data = $this->SiswaService->delete($this->getuser->Tenant, $id);
        return $this->ApiPostResponse($data, "Data Deleted");
    }
}
