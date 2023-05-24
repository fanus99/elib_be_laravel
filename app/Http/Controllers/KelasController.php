<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\DTOs\KelasDTo;
use App\Http\Services\kelasService;
use App\Models\KelasResource;

class KelasController extends BaseController
{
    private $getuser;
    private $kelasService;


    public function __construct(KelasService $kelasService, Request $request)
    {
        $this->getuser = $request->auth;
        $this->kelasService = $kelasService;
    }

    public function GetAll()
    {
        $data = $this->kelasService->GetAll($this->getuser->Tenant);
        return $this->ApiSuccessResponseGet(KelasResource::collection($data));
    }

    public function GetById($id)
    {
        $data = $this->kelasService->GetKelasById($this->getuser->Tenant, $id);
        return $this->ApiSuccessResponseGet(KelasResource::collection($data));
    }

    public function create(KelasDTo $request){
        $data = $this->kelasService->CreateKelas($this->getuser->Tenant, $request);
        return $this->ApiPostResponse($data, "Data Created");
    }

    public function update($id, KelasDTo $request){
        $data = $this->kelasService->UpdateKelas($this->getuser->Tenant, $id, $request);
        return $this->ApiPostResponse($data, "Data Updated");
    }

    public function delete($id){
        $data = $this->kelasService->delete($this->getuser->Tenant, $id);
        return $this->ApiPostResponse($data, "Data Deleted");
    }
}
