<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Carbon;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Http\Services\JWTService;
use App\Http\Services\UserService;

class LoginController extends Controller
{
    protected $jwtService;
    protected $userService;
    public function __construct(JWTService $jwtService, UserService $userService)
    {
        $this->jwtService = $jwtService;
        $this->userService = $userService;
    }

    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = $this->userService->GetUser($request->username, $request->password);
        if($user == null) return response()->json(['status' => 'failed','msg' => 'User not registered']);

        $checkhash = $this->CheckHash($request->password, $user->password);
        if($checkhash == false) return response()->json(['status' => 'failed','msg' => 'username/pass does not match']);

        $IsLicenseValid = $this->userService->CheckLicense($user->Tenant);
        if($IsLicenseValid == false) return response()->json(['status' => 'failed','msg' => $IsLicenseValid->msg]);

        $apikey = $this->jwtService->generatejwt($user);

        $savetoken = DB::table('Auth.AppUser')
              ->where('IdUser', $user->IdUser)
              ->update(['remember_token' => $apikey]);

        if($savetoken) return response()->json(['status'=>'Login Success','token'=>$apikey]);
    }

    public function CheckHash($passinput, $passdb)
    {
        if(Hash::check($passinput, $passdb)){
            return true;
        }

        return false;
    }
}
