<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Auth.AppUser', function (Blueprint $table) {
            $table->bigIncrements('IdUser');
            $table->string('Username', 50)->unique();
            $table->string('Email', 100)->unique();
            $table->string('Phone', 16)->unique();
            $table->string('FullName', 100);
            $table->string('password');
            $table->integer('role');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('remember_token', 255);
            $table->timestamps();
            $table->integer('Tenant')->unsigned();
            $table->foreign('Tenant')->references('IdTenand')->on('Auth.AppTenant');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Auth.AppUser');
    }
};
