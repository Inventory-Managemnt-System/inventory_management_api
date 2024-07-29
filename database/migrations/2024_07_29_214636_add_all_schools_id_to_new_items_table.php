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
           
            $table->dropColumn(['all_school_ids', 'all_school_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('new_items', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeign(['all_school_id']);
            $table->dropColumn('all_school_id');
            
            // Optionally, re-add the old column if needed
            $table->unsignedBigInteger('all_school_ids')->nullable()->after('school');
        });
    }
};
