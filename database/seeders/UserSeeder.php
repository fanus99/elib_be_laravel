<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Auth.AppUser')->insert([
            'Username' =>  "fanus99",
            'Email' => "utinatura@gmail.com",
            'Phone' => "6285156046245",
            'FullName' => "fanus",
            'password' => Hash::make('MakeAmericaGreatAgain'),
            'role' => "1",
            'Tenant' => 1,
            'created_at' => Carbon\Carbon::now(),
        ]);
    }
}
