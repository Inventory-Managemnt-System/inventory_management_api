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
        Schema::table('discrepancies', function (Blueprint $table) {
            $table->integer("school_id")->after("report_id")->nullable();
            $table->integer("location_id")->after("report_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discrepancies', function (Blueprint $table) {
            $table->dropColumn("school_id");
            $table->dropColumn("location_id");
        });
    }
};
