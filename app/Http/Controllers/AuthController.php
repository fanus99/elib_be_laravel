<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\JWTService;
use App\Http\Services\UserService;
use App\Http\Responses\ApiSuccessResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\DTOs\LoginDTo;
use App\DTOs\RegisterDTo;
use App\DTOs\RefreshTokenDTo;
use Carbon;
use DB;

class AuthController extends Controller
{
    private $jwtService;
    private $userService;

    public function __construct(JWTService $jwtService, UserService $userService)
    {
        $this->jwtService = $jwtService;
        $this->userService = $userService;
    }

    public function authenticate(LoginDTo $request)
    {
        $user = $this->userService->GetUser($request->get('Username'));
        if($user == null){
            return new ApiSuccessResponse(
                ['status' => false],
                ['message' =>  'User not registered'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $checkhash = $this->userService->CheckHash($request->get('Password'), $user->password);
        if($checkhash == false)
        {
            return new ApiSuccessResponse(
                ['status' => false],
                ['message' =>  'username/pass does not match'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $IsLicenseValid = $this->userService->CheckLicense($user->Tenant);
        if($IsLicenseValid->statusres == false)
        {
            return new ApiSuccessResponse(
                ['status' => $IsLicenseValid->statusres],
                ['message' =>  $IsLicenseValid->msg],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $apikey = $this->jwtService->generatejwt($user);
        return new ApiSuccessResponse(
            $apikey,
            ['message' => 'Login Success'],
            Response::HTTP_OK
        );
    }

    public function register(RegisterDTo $request)
    {
        $user = $this->userService->GetUser($request->get('Username'), $request->get('Email'), $request->get('Phonenumber'));
        if($user != null){
            return new ApiSuccessResponse(
                ['status' => false],
                ['message' =>  'Username has been registered'],
                Response::HTTP_CONFLICT
            );
        }

        $createUser = $this->userService->CreateUser($request);
        if($createUser){
            return new ApiSuccessResponse(
                ['status' => true],
                ['message' =>  'Registration is successful, please login with the registered username and password'],
                Response::HTTP_CREATED
            );
        }

        return new ApiSuccessResponse(
            ['status' => false],
            ['message' =>  'User could not be created'],
            Response::HTTP_CONFLICT
        );
    }

    public function refreshToken(RefreshTokenDTo $request)
    {
        $refreshToken = $this->jwtService->isValidRefreshToken($request->get('access_token'), $request->get('refresh_token'));

        return $refreshToken;
    }
}
