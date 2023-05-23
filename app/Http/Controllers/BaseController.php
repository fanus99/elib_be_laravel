<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Responses\ApiSuccessResponse;

class BaseController extends Controller
{
    public function ApiSuccessResponseGet($data){
        $message = 'Success get ' . count($data) . ' data';

        return new ApiSuccessResponse(
            $data,
            ['message' => $message],
            Response::HTTP_OK
        );
    }

    public function ApiSuccessResponseGetFirst($data){
        $message = 'Success get data';

        if($data == ""){
            $message = "Data Not Found";
        }

        return new ApiSuccessResponse(
            $data,
            ['message' => $message],
            Response::HTTP_OK
        );
    }

    public function ApiSuccessResponsePost($data, $message){
        return new ApiSuccessResponse(
            $data,
            ['message' => $message],
            Response::HTTP_ACCEPTED
        );
    }

    public function ApiFailedPostResponse($data){
        $message = $data->msg;

        return new ApiSuccessResponse(
            '',
            ['message' => $message],
            Response::HTTP_CONFLICT
        );
    }

    public function ApiPostResponse($data, $message){
        if($data->statusres == false){
            return $this->ApiFailedPostResponse($data);
        }

        return $this->ApiSuccessResponsePost($data, $message);
    }
}
