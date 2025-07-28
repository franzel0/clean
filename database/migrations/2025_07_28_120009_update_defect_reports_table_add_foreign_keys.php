<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('defect_reports', function (Blueprint $table) {
            // Neue Foreign Key Spalte hinzufügen
            $table->foreignId('defect_type_id')->nullable()->after('defect_type')->constrained('defect_types');
        });
    }

    public function down(): void
    {
        Schema::table('defect_reports', function (Blueprint $table) {
            $table->dropForeign(['defect_type_id']);
            $table->dropColumn('defect_type_id');
        });
    }
};
