<?php

namespace App\Http\Services;
use DB;
use Carbon;
use Illuminate\Support\Facades\Hash;

class CheckHash
{
    public function CheckHash($passinput, $passdb)
    {
        if(Hash::check($passinput, $passdb)){
            return true;
        }

        return false;
    }
}
