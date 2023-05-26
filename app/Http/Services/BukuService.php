<?php

namespace App\Http\Services;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\DTOs\BukuDTo;
use App\Http\Services\TenantService;
use App\Models\UniversalResponse;
use Carbon;

class BukuService
{
    public function GetAll($tenant)
    {
        return DB::table('Master.Buku')
                ->where('Tenant', $tenant)
                ->get();
    }

    public function GetBukuById($tenant, $id)
    {
        return DB::table('Master.Buku')
                ->where([
                    ['Tenant', $tenant],
                    ['IdBuku',$id]])
                ->first();
    }

    public function GetBukuByISBN($tenant, $ISBN, $id = 0){
        $getData = DB::table('Master.Buku')
                    ->where([
                        ['Tenant', $tenant],
                        ['ISBN',$ISBN],
                    ]);

        if($id != 0){
            $getData->where('IdBuku', '!=', $id);
        }

        return $getData->first();
    }

    public function CreateBuku($tenant, $request){
        $checkDuplicate = $this->CheckDuplicateCreate($tenant, $request);

        if($checkDuplicate->statusres != true){
            return $checkDuplicate;
        }

        $urllink = $this->uploadCover($request);

        $lastInsertId = DB::table('Master.Buku')->insertGetId([
            'JudulBuku' => $request->JudulBuku,
            'Pengarang' => $request->Pengarang,
            'Edisi' => $request->Edisi,
            'ISBN' => $request->ISBN,
            'Penerbit' => $request->Penerbit,
            'TahunTerbit' => $request->TahunTerbit,
            'TempatTerbit' => $request->TempatTerbit,
            'Abstrak' => $request->Abstrak,
            'DeskripsiFisik' => $request->DeskripsiFisik,
            'JumlahEksemplar' => $request->JumlahEksemplar,
            'TanggalMasuk' => $request->TanggalMasuk,
            'CoverBuku' => $urllink,
            'TipeKoleksi' => $request->TipeKoleksi,
            'Lokasi' => $request->Lokasi,
            'Bahasa' => $request->Bahasa,
            'Tenant' => $tenant,

        ], 'IdBuku');

        return $this->GetBukuById($tenant, $lastInsertId);
    }

    public function uploadCover($request){
        $original_filename = $request->file('CoverBuku')->getClientOriginalName();
        $original_filename_arr = explode('.', $original_filename);
        $file_ext = end($original_filename_arr);
        $destination_path = './CoverBuku/';
        $image = 'U-' . time() . '.' . $file_ext;

        if ($request->file('CoverBuku')->move($destination_path, $image)) {
            $urllink = request()->getHttpHost() . '/CoverBuku/' . $image;
            return $urllink;
        } else {
            return false;
        }
    }

    public function CheckDuplicateCreate($tenant, $request){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $getBuku = $this->GetBukuByISBN($tenant, $request->ISBN);

        if($getBuku != null){
            $returnres->statusres = false;
            $returnres->msg = "ISBN duplicate";

            return $returnres;
        }

        return $returnres;
    }

    public function UpdateBuku($tenant, $id, $request){
        $checkDuplicate = $this->CheckDuplicateUpdate($tenant, $request, $id);

        if($checkDuplicate->statusres != true){
            return $checkDuplicate;
        }
        $urllink = $request->coverOld;

        if($request->CoverBuku != null){
            $urllink = $this->uploadCover($request);
        }

        DB::table('Master.Buku')
            ->where('IdBuku', $id)
            ->update([
                'JudulBuku' => $request->JudulBuku,
                'Pengarang' => $request->Pengarang,
                'Edisi' => $request->Edisi,
                'ISBN' => $request->ISBN,
                'Penerbit' => $request->Penerbit,
                'TahunTerbit' => $request->TahunTerbit,
                'TempatTerbit' => $request->TempatTerbit,
                'Abstrak' => $request->Abstrak,
                'DeskripsiFisik' => $request->DeskripsiFisik,
                'JumlahEksemplar' => $request->JumlahEksemplar,
                'TanggalMasuk' => $request->TanggalMasuk,
                'CoverBuku' => $urllink,
                'TipeKoleksi' => $request->TipeKoleksi,
                'Lokasi' => $request->Lokasi,
                'Bahasa' => $request->Bahasa,
                'Tenant' => $tenant,
            ]);

        return $this->GetBukuById($tenant, $id);
    }

    public function CheckDuplicateUpdate($tenant, $request, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $getBuku = $this->GetBukuById($tenant, $id);
        if($getBuku == null){
            $returnres->statusres = false;
            $returnres->msg = "Data not Found";

            return $returnres;
        }

        $getDuplicateData = $this->GetBukuByISBN($tenant, $request->ISBN, $id);

        if($getDuplicateData != null){
            $returnres->statusres = false;
            $returnres->msg = "ISBN duplicate";

            return $returnres;
        }

        $returnres->data = $getBuku;
        return $returnres;
    }

    public function delete($tenant, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $deleteData = DB::table('Master.Buku')
                        ->where([
                            ['Tenant', $tenant],
                            ['IdBuku',$id]
                        ])
                        ->delete();

        if($deleteData == 0){
            $returnres->statusres = false;
            $returnres->msg = "Data not found";
        }

        return $returnres;
    }

}
