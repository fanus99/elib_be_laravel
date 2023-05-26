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
        Schema::create('Master.Buku', function (Blueprint $table) {
            $table->bigIncrements('IdBuku');
            $table->string('JudulBuku', 100);
            $table->string('Pengarang', 100);
            $table->string('Edisi', 100);
            $table->string('ISBN', 30);
            $table->string('Penerbit', 100);
            $table->string('TahunTerbit', 4);
            $table->string('TempatTerbit', 4);
            $table->string('Abstrak', 30);
            $table->string('DeskripsiFisik', 30);
            $table->integer('JumlahEksemplar')->default(1);
            $table->timestamp('TanggalMasuk')->useCurrent();
            $table->string('CoverBuku', 250);
            $table->string('TipeKoleksi', 100);
            $table->string('Lokasi', 100);
            $table->string('Bahasa', 100);
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
        Schema::dropIfExists('Master.Buku');
    }
};
