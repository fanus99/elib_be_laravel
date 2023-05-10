<?php

namespace App\Http\Services;
use DB;
use Carbon;

class UserService
{
    public function GetUser($username, $password)
    {
        $users = DB::table('Auth.AppUser')
                    ->where('Username', $username)
                    ->orWhere('Email', $username)
                    ->orWhere('Phone', $username)->first();

        return $users;
    }

    public function CheckLicense($tenantId){
        $tenant = DB::table('Auth.AppTenant')->where('IdTenand', $tenantId)->first();

        if($tenant->ExpiredOn < Carbon\Carbon::now()) return response()->json(['status' => false, 'msg' => 'License expired']);
        if($tenant->IsLocked) return response()->json(['status' => false, 'msg' => 'Account has been locked for violation']);

        return response()->json(['status' => true, 'msg' => 'Valid']);
    }
}
