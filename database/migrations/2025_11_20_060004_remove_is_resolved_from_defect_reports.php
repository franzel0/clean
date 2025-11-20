<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if is_resolved column exists before trying to drop it
        if (Schema::hasColumn('defect_reports', 'is_resolved')) {
            // Copy data from is_resolved to is_completed if needed
            DB::statement('UPDATE defect_reports SET is_completed = is_resolved WHERE is_completed = 0 AND is_resolved = 1');
            
            Schema::table('defect_reports', function (Blueprint $table) {
                $table->dropColumn('is_resolved');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('defect_reports', 'is_resolved')) {
            Schema::table('defect_reports', function (Blueprint $table) {
                $table->boolean('is_resolved')->default(false)->after('photos');
            });
            
            // Copy data back
            DB::statement('UPDATE defect_reports SET is_resolved = is_completed');
        }
    }
};
