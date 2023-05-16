<?php

namespace App\Http\Services;
use DB;
use Carbon;
use App\Models\UniversalResponse;

class CheckLicense extends CheckHash
{
    public function CheckLicense($tenantId){
        $tenant = DB::table('Auth.AppTenant')->where('IdTenand', $tenantId)->first();
        $returnres = new UniversalResponse();
        $returnres->statusres = true;
        $returnres->msg = "Valid";

        if($tenant->ExpiredOn < Carbon\Carbon::now())
        {
            $returnres->statusres = false;
            $returnres->msg = "License expired";
        }

        if($tenant->IsLocked)
        {
            $returnres->statusres = false;
            $returnres->msg = "Account has been locked for violation";
        }

        return $returnres;
    }
}
