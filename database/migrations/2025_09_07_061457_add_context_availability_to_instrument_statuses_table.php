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
        Schema::table('instrument_statuses', function (Blueprint $table) {
            // Verfügbarkeit in verschiedenen Kontexten
            $table->boolean('available_in_purchase_orders')->default(false);
            $table->boolean('available_in_defect_reports')->default(false);
            $table->boolean('available_in_instruments')->default(true);
            $table->boolean('available_in_containers')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instrument_statuses', function (Blueprint $table) {
            $table->dropColumn([
                'available_in_purchase_orders',
                'available_in_defect_reports', 
                'available_in_instruments',
                'available_in_containers'
            ]);
        });
    }
};
