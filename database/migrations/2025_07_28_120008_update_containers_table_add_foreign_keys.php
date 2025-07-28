<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('containers', function (Blueprint $table) {
            // Neue Foreign Key Spalten hinzufügen
            $table->foreignId('type_id')->nullable()->after('type')->constrained('container_types');
            $table->foreignId('status_id')->nullable()->after('status')->constrained('container_statuses');
        });
    }

    public function down(): void
    {
        Schema::table('containers', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropForeign(['status_id']);
            $table->dropColumn(['type_id', 'status_id']);
        });
    }
};
