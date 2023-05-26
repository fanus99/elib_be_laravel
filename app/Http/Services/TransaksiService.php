<?php

namespace App\Http\Services;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\TenantService;
use App\Models\UniversalResponse;
use Carbon;

class TransaksiService
{
    public function GetAll($tenant)
    {
        return DB::table('Transaction.Peminjaman')
                ->LeftJoin('Master.Siswa', 'Master.Siswa.IdSiswa', 'Transaction.Peminjaman.Siswa')
                ->leftjoin('Master.Buku', 'Master.Buku.IdBuku', 'Transaction.Peminjaman.Buku')
                ->select(
                    'Transaction.Peminjaman.IdPeminjaman',
                    'Master.Siswa.Nama as Siswa',
                    'Master.Buku.JudulBuku as Buku',
                    'Transaction.Peminjaman.TanggalPinjam',
                    'Transaction.Peminjaman.BatasPengembalian',
                    'Transaction.Peminjaman.TanggalPengembalian',
                    'Transaction.Peminjaman.created_at',
                    'Transaction.Peminjaman.updated_at',
                )->where('Transaction.Peminjaman.Tenant', $tenant)
                ->get();
    }

    public function GetPeminjamanById($tenant, $id)
    {
        return DB::table('Transaction.Peminjaman')
                ->LeftJoin('Master.Siswa', 'Master.Siswa.IdSiswa', 'Transaction.Peminjaman.Siswa')
                ->leftjoin('Master.Buku', 'Master.Buku.IdBuku', 'Transaction.Peminjaman.Buku')
                ->select(
                    'Transaction.Peminjaman.IdPeminjaman',
                    'Master.Siswa.Nama  as Siswa',
                    'Master.Buku.JudulBuku as Buku',
                    'Transaction.Peminjaman.TanggalPinjam',
                    'Transaction.Peminjaman.BatasPengembalian',
                    'Transaction.Peminjaman.TanggalPengembalian',
                    'Transaction.Peminjaman.created_at',
                    'Transaction.Peminjaman.updated_at',
                )->where([['Transaction.Peminjaman.Tenant', $tenant], ['IdPeminjaman',$id]])
                ->first();
    }

    public function CreatePeminjaman($tenant, $request){
        $lastInsertId = DB::table('Transaction.Peminjaman')->insertGetId([
            'TanggalPinjam' => $request->get('TanggalPinjam'),
            'BatasPengembalian' => $request->get('BatasPengembalian'),
            'Siswa' => $request->get('Siswa'),
            'Buku' => $request->get('Buku'),
            'Tenant' => $tenant
        ], 'IdPeminjaman');

        return $this->GetPeminjamanById($tenant, $lastInsertId);
    }

    public function UpdatePeminjaman($tenant, $id, $request){
        DB::table('Transaction.Peminjaman')
            ->where('IdPeminjaman', $id)
            ->update([
                'TanggalPinjam' => $request->get('TanggalPinjam'),
                'BatasPengembalian' => $request->get('BatasPengembalian'),
                'Siswa' => $request->get('Siswa'),
                'Buku' => $request->get('Buku'),
            ]);

        return $this->GetPeminjamanById($tenant, $id);
    }

    public function delete($tenant, $id){
        $returnres = new UniversalResponse();
        $returnres->statusres = true;

        $deleteData = DB::table('Transaction.Peminjaman')
                        ->where([
                            ['Tenant', $tenant],
                            ['IdPeminjaman',$id]
                        ])
                        ->delete();

        if($deleteData == 0){
            $returnres->statusres = false;
            $returnres->msg = "Data not found";
        }

        return $returnres;
    }

    public function pengembalian($tenant, $id){
        DB::table('Transaction.Peminjaman')
        ->where('IdPeminjaman', $id)
        ->update([
            'TanggalPengembalian' => Carbon\Carbon::now(),
        ]);

        return $this->GetPeminjamanById($tenant, $id);
    }

}
