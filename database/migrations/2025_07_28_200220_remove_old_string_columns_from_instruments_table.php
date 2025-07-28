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
        Schema::table('instruments', function (Blueprint $table) {
            // Remove old string columns that have been replaced by foreign keys
            $table->dropColumn(['manufacturer', 'category', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            // Re-add the old columns if migration is rolled back
            $table->string('manufacturer')->nullable()->after('serial_number');
            $table->string('category')->after('model');
            $table->string('status')->default('available')->after('description');
        });
    }
};
