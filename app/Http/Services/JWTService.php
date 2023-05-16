<?php

namespace App\Http\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Str;
use DB;
use Carbon;

class JWTService
{
    function generatejwt($user) {
        $key = env('JWT_SECRET');

        $payload = [
            'iss' => "localhost:8000",
            'sub' => $user->IdUser,
            'iat' => time(),
            'exp' => time() + 60*60
        ];
        $accessToken = JWT::encode($payload, $key, 'HS256');
        $refreshToken = $this->generateRefreshToken();
        $updateRememberToken = $this->updateRememberToken($user->IdUser, $accessToken);
        $updateTokenDB = $this->updateRefreshToken($user->IdUser,  $refreshToken);

        $token = ['access_token' => $accessToken, 'refresh_token' => $refreshToken];

        return $token;
    }

    function generateRefreshToken(){
        return base64_encode(Str::random(30));
    }

    function updateRememberToken($idUser, $accessToken){
        $affected = DB::table('Auth.AppUser')
            ->where('IdUser', $idUser)
            ->update(['remember_token' => $accessToken]);

            return $affected;
    }

    function updateRefreshToken($idUser, $refreshToken){
        $createdAt = Carbon\Carbon::now();
        $expiredAt = Carbon\Carbon::now()->addDays(5);
        $affected = DB::table('Auth.AppRefreshToken')
                    ->updateOrInsert(
                        ['user' => $idUser],
                        ['RefreshToken' => $refreshToken, 'ExpiredAt' => $expiredAt, 'updated_at' => $createdAt],
                    );

        return $affected;
    }
}
