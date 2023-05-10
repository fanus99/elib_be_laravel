<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Auth.AppSubscriptionType', function (Blueprint $table) {
            $table->bigIncrements('IdType');
            $table->string('Name', 100);
            $table->integer('LimitUser')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Auth.AppSubscriptionType');
    }
};
