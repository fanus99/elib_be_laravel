<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Transaction.Peminjaman', function (Blueprint $table) {
            $table->bigIncrements('IdPeminjaman');
            $table->timestamp('TanggalPinjam')->useCurrent();
            $table->timestamp('BatasPengembalian');
            $table->timestamp('TanggalPengembalian')->nullable();;
            $table->integer('Siswa')->unsigned();
            $table->foreign('Siswa')->references('IdSiswa')->on('Master.Siswa');
            $table->integer('Buku')->unsigned();
            $table->foreign('Buku')->references('IdBuku')->on('Master.Buku');
            $table->integer('Tenant')->unsigned();
            $table->foreign('Tenant')->references('IdTenand')->on('Auth.AppTenant');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Transaction.Peminjaman');
    }
};
