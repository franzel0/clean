<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            // Neue Foreign Key Spalten hinzufügen
            $table->foreignId('category_id')->nullable()->after('model')->constrained('instrument_categories');
            $table->foreignId('status_id')->nullable()->after('status')->constrained('instrument_statuses');
            
            // Alte String-Spalten entfernen (nach Datenmigration)
            // $table->dropColumn(['category', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['status_id']);
            $table->dropColumn(['category_id', 'status_id']);
        });
    }
};
