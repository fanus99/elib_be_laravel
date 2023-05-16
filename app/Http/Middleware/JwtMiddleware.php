<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use App\Http\Services\UserService;
use DB;

class JwtMiddleware
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('access_token');
        $key = env('JWT_SECRET');

        if(!$token) {
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, new Key($key, 'HS256'));
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }

        $user = $this->userService->GetUserById($credentials->sub);

        $request->auth = $user;

        return $next($request);
    }
}
