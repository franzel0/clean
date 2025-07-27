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
        Schema::table('containers', function (Blueprint $table) {
            $table->string('status')->default('complete')->after('is_active');
            // 'complete' = vollständig funktionsfähig
            // 'incomplete' = enthält defekte Instrumente, aber verwendbar
            // 'out_of_service' = nicht verwendbar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('containers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
