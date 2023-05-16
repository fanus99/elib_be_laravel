<?php

namespace App\Http\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;
use DB;
use Carbon;
use App\Http\Services\UserService;
use App\Models\UniversalResponse;

class JWTService
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

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
        $updateTokenDB = $this->updateRefreshToken($user->IdUser, $refreshToken);

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

    function isValidRefreshToken($accessToken, $refreshToken){
        $IdUser = $this->getUserIdFromToken($accessToken);
        $refreshToken = DB::table('Auth.AppRefreshToken')->where([['user',$IdUser],['RefreshToken',$refreshToken]])->first();

        if(!$refreshToken){
            $returnres = new UniversalResponse();
            $returnres->statusres = false;
            $returnres->msg = "Unknown Refresh Token";

            return response()->json($returnres);
        }

        $now = Carbon\Carbon::now()->toDateTimeString();

        if($now <= $refreshToken->ExpiredAt){
            $user = $this->userService->GetUserById($IdUser);
            $token = $this->generatejwt($user);

            return $token;
        }

        $returnres = new UniversalResponse();
        $returnres->statusres = false;
        $returnres->msg = "Valid";

        return response()->json($returnres);
    }

    function getUserIdFromToken($accessToken){
        $key = env('JWT_SECRET');
        $credentials = JWT::decode($accessToken, new Key($key, 'HS256'));

        return $credentials->sub;
    }
}
