<?php

namespace App\Http\Services;
use DB;
use Illuminate\Support\Facades\Hash;
use App\DTOs\RegisterDTo;
use App\Http\Services\TenantService;
use Carbon;

class UserService extends UserSecurity
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function GetUser($username, $email = '', $phone = '')
    {
        if($email == "" || $phone == ""){
            $email = $username;
            $phone = $username;
        }

        $users = DB::table('Auth.AppUser')
                    ->where('Username', $username)
                    ->orWhere('Email', $email)
                    ->orWhere('Phone', $phone)
                    ->first();

        return $users;
    }

    public function GetUserById($id)
    {
        $users = DB::table('Auth.AppUser')
                    ->where('IdUser', $id)
                    ->first();

        return $users;
    }

    public function CreateUser(RegisterDTo $data){
        $getTenantId = $this->tenantService->CreateTenant($data->get('InstitutionName'));
        $createUser = DB::table('Auth.AppUser')->insert([
            'Username' =>  $data->get('Username'),
            'Email' => $data->get('Email'),
            'Phone' =>  $data->get('Phonenumber'),
            'FullName' =>  $data->get('FullName'),
            'password' => Hash::make($data->get('Password')),
            'role' => "1",
            'Tenant' => $getTenantId,
            'created_at' => Carbon\Carbon::now(),
        ]);

        return $createUser;
    }
}
