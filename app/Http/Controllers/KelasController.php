<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Carbon;
use Illuminate\Support\Str;

class KelasController extends Controller
{
    private $getuser;

    public function __construct(Request $request)
    {
        $this->getuser = $request->auth;
    }

    public function test()
    {
        return response()->json(['status' => 'success','msg' => $this->getuser]);
        return response()->json(['status' => 'success','msg' => 'anda telah login']);
    }
}
