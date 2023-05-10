<?php

namespace App\Http\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Str;

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

        return JWT::encode($payload, $key, 'HS256');
    }
}
