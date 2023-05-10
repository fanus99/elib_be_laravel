<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Carbon;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $getuser = $this->GetUser($request->username, $request->password);
        if($getuser == null) return response()->json(['status' => 'failed','msg' => 'User not registered']);

        $checkhash = $this->CheckHash($request->password, $getuser->password);
        if($checkhash == false) return response()->json(['status' => 'failed','msg' => 'username/pass does not match']);

        $IsLicenseValid = $this->CheckLicense($getuser->Tenant);
        if($IsLicenseValid == false) return response()->json(['status' => 'failed','msg' => $IsLicenseValid->msg]);

        $apikey = $this->jwt($getuser);

        $savetoken = DB::table('Auth.AppUser')
              ->where('IdUser', $getuser->IdUser)
              ->update(['remember_token' => $apikey]);

        if($savetoken) return response()->json(['status'=>'Login Success','token'=>$apikey]);
    }

    public function GetUser($username, $password)
    {
        $users = DB::table('Auth.AppUser')
                    ->where('Username', $username)
                    ->orWhere('Email', $username)
                    ->orWhere('Phone', $username)->first();

        return $users;
    }

    public function CheckHash($passinput, $passdb)
    {
        if(Hash::check($passinput, $passdb)){
            return true;
        }

        return false;
    }

    public function CheckLicense($tenantId){
        $tenant = DB::table('Auth.AppTenant')->where('IdTenand', $tenantId)->first();

        if($tenant->ExpiredOn < Carbon\Carbon::now()) return response()->json(['status' => false, 'msg' => 'License expired']);
        if($tenant->IsLocked) return response()->json(['status' => false, 'msg' => 'Account has been locked for violation']);

        return response()->json(['status' => true, 'msg' => 'Valid']);;
    }

    public function generate_jwtauth($user){
        $key = env('API_KEY');

        $payload = JWTFactory::sub($user->IdUser)
            ->myCustomString('Foo Bar')
            ->myCustomArray(['Apples', 'Oranges'])
            ->myCustomObject($user)
            ->make();

        $jwt = JWT::encode($payload, $key);
        return $jwt;
    }

    protected function jwt($user) {
        $key = env('JWT_SECRET');

        $payload = [
            'iss' => "localhost:8000",
            'sub' => $user->IdUser,
            'iat' => time(),
            'exp' => time() + 60*60
        ];

        return JWT::encode($payload, $key, 'HS256');
    }
}
