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
        Schema::create('Master.Siswa', function (Blueprint $table) {
            $table->bigIncrements('IdSiswa');
            $table->string('Nama', 255);
            $table->string('NIS', 255);
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
        Schema::dropIfExists('Master.Siswa');
    }
};
