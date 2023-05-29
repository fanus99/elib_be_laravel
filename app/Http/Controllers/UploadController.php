<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\UploadService;

class UploadController extends BaseController
{
    private $getuser;
    private $uploadService;


    public function __construct(UploadService $uploadService, Request $request)
    {
        $this->getuser = $request->auth;
        $this->uploadService = $uploadService;
    }

    public function upload(Request $request)
    {
        $data = $this->uploadService->upload($request);
        return $this->ApiSuccessResponseGetFirst($data);
    }

    public function delete(Request $request)
    {
        $data = $this->uploadService->delete($request);
        return $this->ApiSuccessResponseGetFirst($data);
    }
}
