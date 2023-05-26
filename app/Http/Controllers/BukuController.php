<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\DTOs\BukuDTo;
use App\Http\Services\TransaksiService;
use App\Models\BukuResource;

class TransaksiController extends BaseController
{
    private $getuser;
    private $TransaksiService;


    public function __construct(TransaksiService $TransaksiService, Request $request)
    {
        $this->getuser = $request->auth;
        $this->TransaksiService = $TransaksiService;
    }

    public function GetAll()
    {
        $data = $this->TransaksiService->GetAll($this->getuser->Tenant);
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
