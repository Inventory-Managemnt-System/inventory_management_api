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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string("unique_id")->unique();
            $table->string("bar_code")->unique();
            $table->string("name");
            $table->text("description");
            $table->string("brand");
            $table->string("category")->nullable();
            $table->string("value");
            $table->string("image");

            $table->float("unit_cost");
            $table->integer("quantity");
            $table->integer("reorder_point");
            $table->string("supplier");
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
        Schema::dropIfExists('items');
    }
};
