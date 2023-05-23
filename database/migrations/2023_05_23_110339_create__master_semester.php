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
        Schema::create('Master.Semester', function (Blueprint $table) {
            $table->bigIncrements('IdSemester');
            $table->string('TahunAjaran', 255);
            $table->integer('Semester');
            $table->boolean('IsActive')->default(false);
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
        Schema::dropIfExists('Master.Semester');
    }
};
