<?php

namespace App\Http\Services;
use DB;
use Illuminate\Support\Facades\Hash;
use App\DTOs\SiswaDTo;
use App\Http\Services\TenantService;
use App\Models\UniversalResponse;
use Carbon;
use Illuminate\Support\Facades\File;

class UploadService
{
    public function upload($request)
    {
        $original_filename = $request->file('fileUpload')->getClientOriginalName();
        $original_filename_arr = explode('.', $original_filename);
        $file_ext = end($original_filename_arr);
        $destination_path = './CoverBuku/';
        $image = 'U-' . time() . '.' . $file_ext;

        if ($request->file('fileUpload')->move($destination_path, $image)) {
            $urllink = request()->getHttpHost() . '/CoverBuku/' . $image;
            return $urllink;
        } else {
            return false;
        }
    }

    public function delete($request)
    {
        $returnres = new UniversalResponse();
        $returnres->statusres = true;
        $request->fileLocation = str_replace(request()->getHttpHost(), "", $request->fileLocation);
        if(!File::exists("./".$request->fileLocation)){
            $returnres->statusres = false;
            $returnres->msg = "File not found";

            return $returnres;
        }

        File::delete("./".$request->fileLocation);

        return $returnres;
    }

}
