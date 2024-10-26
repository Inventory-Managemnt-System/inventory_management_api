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
        Schema::table('schools', function (Blueprint $table) {
            $table->integer("qa_id")->after("school_id")->nullable();
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->integer("qa_id")->after("id")->nullable();
        });
    }


        
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn("qa_id");
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn("qa_id");
        });
    }
};
