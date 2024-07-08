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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('school_image')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('level');
            $table->string('logo')->nullable();
            $table->string("address");
            $table->string("city");
            $table->string("lga");
            $table->string("postal_code");
            $table->string("status")->default("pending");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};