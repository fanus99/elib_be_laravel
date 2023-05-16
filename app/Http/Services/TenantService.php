<?php

namespace App\Http\Services;
use DB;
use Carbon;
use Illuminate\Support\Str;

class TenantService
{
    public function CreateTenant($InstitutionName)
    {
        $activatedOn = Carbon\Carbon::now();
        $expiredOn = Carbon\Carbon::now()->addDays(7);
        $subscriptionType = $this->getIdAppSubscriptionType('Full Version Demo');
        $getTenantId = DB::table('Auth.AppTenant')->insertGetId(
                            [
                                'License' => Str::random(30),
                                'InstitutionName' => $InstitutionName,
                                'ActivatedOn' => $activatedOn,
                                'ExpiredOn' => $expiredOn,
                                'IsLocked' => false,
                                'created_at' => $activatedOn,
                                'SubscriptionType' => $subscriptionType,
                            ]
                            , 'IdTenand');

        return $getTenantId;
    }

    public function getIdAppSubscriptionType($type):int
    {
        $getIdSubsriptionType = DB::table('Auth.AppSubscriptionType')
                                ->where('Name', '=', $type)
                                ->first();

        return $getIdSubsriptionType->IdType;
    }
}
