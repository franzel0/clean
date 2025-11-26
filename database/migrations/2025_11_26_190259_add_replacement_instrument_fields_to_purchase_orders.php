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
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Alte Instrument-Referenz (vom defect_report)
            $table->foreignId('old_instrument_id')->nullable()->after('defect_report_id')->constrained('instruments')->onDelete('set null');
            
            // Neues Instrument - entweder Referenz oder Freitext
            $table->foreignId('new_instrument_id')->nullable()->after('old_instrument_id')->constrained('instruments')->onDelete('set null');
            $table->text('replacement_instrument_description')->nullable()->after('new_instrument_id')->comment('Freitexteingabe wenn keine Alternative aus Katalog');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['old_instrument_id']);
            $table->dropColumn('old_instrument_id');
            $table->dropForeignKeyIfExists(['new_instrument_id']);
            $table->dropColumn('new_instrument_id');
            $table->dropColumn('replacement_instrument_description');
        });
    }
};
