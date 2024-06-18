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
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->string("item_name");
            $table->string("item_description");
            $table->string("brand");
            $table->string("category");
            $table->string("priority");
            $table->string("address");
            $table->string("picking_area");
            $table->string("building_number");
            $table->string("action");
            $table->string("reference_number");
            $table->string("additional_info")->nullable();
            $table->string("time_moved");
            $table->string("date_moved");
            $table->string("status")->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};
