<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\DTOs\PeminjamanDTo;
use App\Http\Services\TransaksiService;
use App\Models\PeminjamanResource;

class TransaksiController extends BaseController
{
    private $getuser;
    private $transaksiService;


    public function __construct(TransaksiService $transaksiService, Request $request)
    {
        $this->getuser = $request->auth;
        $this->transaksiService = $transaksiService;
    }

    public function GetAll()
    {
        $data = $this->transaksiService->GetAll($this->getuser->Tenant);
        return $this->ApiSuccessResponseGet(PeminjamanResource::collection($data));
    }

    public function GetById($id)
    {
        $data = $this->transaksiService->GetPeminjamanById($this->getuser->Tenant, $id);
        return $this->ApiSuccessResponseGetFirst(new PeminjamanResource($data));
    }

    public function create(PeminjamanDTo $request){
        $data = $this->transaksiService->CreatePeminjaman($this->getuser->Tenant, $request);
        return $this->ApiPostResponse($data, "Data Created");
    }

    public function update($id, PeminjamanDTo $request){
        $data = $this->transaksiService->UpdatePeminjaman($this->getuser->Tenant, $id, $request);
        return $this->ApiPostResponse($data, "Data Updated");
    }

    public function delete($id){
        $data = $this->transaksiService->delete($this->getuser->Tenant, $id);
        return $this->ApiPostResponse($data, "Data Deleted");
    }

    public function pengembalian($id){
        $data = $this->transaksiService->pengembalian($this->getuser->Tenant, $id);
        return $this->ApiPostResponse($data, "Data Updated");
    }
}
