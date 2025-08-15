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
        // Add foreign key for users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });

        // Add indexes for better performance
        Schema::table('instruments', function (Blueprint $table) {
            $table->index(['is_active', 'status_id']);
            $table->index(['current_container_id']);
            $table->index(['current_location_id']);
            $table->index(['category_id', 'is_active']);
        });

        Schema::table('containers', function (Blueprint $table) {
            $table->index(['is_active', 'status_id']);
            $table->index(['type_id', 'is_active']);
            $table->index(['current_location_id']);
        });

        Schema::table('defect_reports', function (Blueprint $table) {
            $table->index(['status', 'severity']);
            $table->index(['reported_at']);
            $table->index(['instrument_id', 'status']);
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->index(['status_id', 'order_date']);
            $table->index(['supplier_id']);
            $table->index(['manufacturer_id']);
        });

        Schema::table('instrument_movements', function (Blueprint $table) {
            $table->index(['instrument_id', 'performed_at']);
            $table->index(['movement_type', 'performed_at']);
        });

        // Add unique constraints
        Schema::table('instrument_categories', function (Blueprint $table) {
            $table->unique(['name']);
        });

        Schema::table('instrument_statuses', function (Blueprint $table) {
            $table->unique(['name']);
        });

        Schema::table('container_types', function (Blueprint $table) {
            $table->unique(['name']);
        });

        Schema::table('container_statuses', function (Blueprint $table) {
            $table->unique(['name']);
        });

        Schema::table('defect_types', function (Blueprint $table) {
            $table->unique(['name']);
        });

        Schema::table('purchase_order_statuses', function (Blueprint $table) {
            $table->unique(['name']);
        });

        Schema::table('manufacturers', function (Blueprint $table) {
            $table->unique(['name']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop unique constraints
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('manufacturers', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('purchase_order_statuses', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('defect_types', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('container_statuses', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('container_types', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('instrument_statuses', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('instrument_categories', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        // Drop indexes
        Schema::table('instrument_movements', function (Blueprint $table) {
            $table->dropIndex(['instrument_id', 'performed_at']);
            $table->dropIndex(['movement_type', 'performed_at']);
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropIndex(['status_id', 'order_date']);
            $table->dropIndex(['supplier_id']);
            $table->dropIndex(['manufacturer_id']);
        });

        Schema::table('defect_reports', function (Blueprint $table) {
            $table->dropIndex(['status', 'severity']);
            $table->dropIndex(['reported_at']);
            $table->dropIndex(['instrument_id', 'status']);
        });

        Schema::table('containers', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'status_id']);
            $table->dropIndex(['type_id', 'is_active']);
            $table->dropIndex(['current_location_id']);
        });

        Schema::table('instruments', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'status_id']);
            $table->dropIndex(['current_container_id']);
            $table->dropIndex(['current_location_id']);
            $table->dropIndex(['category_id', 'is_active']);
        });

        // Drop foreign key for users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });
    }
};
