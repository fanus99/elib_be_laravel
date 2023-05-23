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
        Schema::create('Auth.AppRefreshToken', function (Blueprint $table) {
            $table->id();
            $table->string('RefreshToken', 255);
            $table->timestamp('ExpiredAt')->nullable();
            $table->integer('user')->unsigned();
            $table->foreign('user')->references('IdUser')->on('Auth.AppUser');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Auth.AppRefreshToken');
    }
};
