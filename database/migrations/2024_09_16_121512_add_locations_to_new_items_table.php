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
        Schema::table('new_items', function (Blueprint $table) {
            $table->string("current_location")->after("id")->nullable();
            $table->string("start_location")->after("id")->default("warehouse");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('new_items', function (Blueprint $table) {
            //
        });
    }
};
