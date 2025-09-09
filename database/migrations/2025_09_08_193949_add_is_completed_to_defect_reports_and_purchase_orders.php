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
        Schema::table('defect_reports', function (Blueprint $table) {
            $table->boolean('is_completed')->default(false)->after('resolution_notes');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->boolean('is_completed')->default(false)->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defect_reports', function (Blueprint $table) {
            $table->dropColumn('is_completed');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('is_completed');
        });
    }
};
