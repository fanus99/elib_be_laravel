<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Carbon;
use Illuminate\Support\Str;
use App\Http\Responses\ApiSuccessResponse;
use Illuminate\Http\Response;

class KelasController extends Controller
{
    private $getuser;

    public function __construct(Request $request)
    {
        $this->getuser = $request->auth;
    }

    public function test()
    {
        return new ApiSuccessResponse(
            $this->getuser,
            ['message' => 'User was created successfully'],
            Response::HTTP_OK
        );
    }
}
