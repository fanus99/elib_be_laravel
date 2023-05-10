<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Auth.AppTenant', function (Blueprint $table) {
            $table->bigIncrements('IdTenand');
            $table->string('License', 30);
            $table->string('InstitutionName', 50);
            $table->dateTime('ActivatedOn');
            $table->dateTime('ExpiredOn');
            $table->boolean('IsLocked');
            $table->timestamps();
            $table->integer('SubscriptionType')->unsigned();
            $table->foreign('SubscriptionType')->references('IdType')->on('Auth.AppSubscriptionType');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Auth.AppTenant');
    }
};
