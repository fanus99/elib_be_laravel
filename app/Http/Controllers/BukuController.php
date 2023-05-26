<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\DTOs\BukuDTo;
use App\Http\Services\BukuService;
use App\Models\BukuResource;

class BukuController extends BaseController
{
    private $getuser;
    private $BukuService;


    public function __construct(BukuService $BukuService, Request $request)
    {
        $this->getuser = $request->auth;
        $this->BukuService = $BukuService;
    }

    public function GetAll()
    {
        $data = $this->BukuService->GetAll($this->getuser->Tenant);
        return $this->ApiSuccessResponseGet(BukuResource::collection($data));
    }

    public function GetById($id)
    {
        $data = $this->BukuService->GetBukuById($this->getuser->Tenant, $id);
        return $this->ApiSuccessResponseGetFirst(new BukuResource($data));
    }

    public function create(Request $request){
        $data = $this->BukuService->CreateBuku($this->getuser->Tenant, $request);
        return $this->ApiPostResponse($data, "Data Created");
    }

    public function update(Request $request, $id){
        $data = $this->BukuService->UpdateBuku($this->getuser->Tenant, $id, $request);
        return $this->ApiPostResponse($data, "Data Updated");
    }

    public function delete($id){
        $data = $this->BukuService->delete($this->getuser->Tenant, $id);
        return $this->ApiPostResponse($data, "Data Deleted");
    }
}
