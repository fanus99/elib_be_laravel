<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon;

class AppTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Auth.AppTenant')->insert([
            'License' =>  Str::random(30),
            'InstitutionName' => "PENS",
            'ActivatedOn' => Carbon\Carbon::now(),
            'ExpiredOn' => Carbon\Carbon::now(),
            'IsLocked' => false,
            'created_at' => Carbon\Carbon::now(),
            'SubscriptionType' => 1,
        ]);
    }
}
