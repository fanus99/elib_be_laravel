<?php

namespace App\Http\Services;
use DB;

class CheckLicenseService
{
    public function CheckLicense($Tenant)
    {
        $users = DB::table('Auth.AppUser')
                    ->where('Username', $username)
                    ->orWhere('Email', $username)
                    ->orWhere('Phone', $username)->first();

        return $users;
    }
}
