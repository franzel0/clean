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
        // Containers
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('barcode')->unique()->nullable();
            $table->text('description')->nullable();
            $table->integer('capacity')->nullable();
            $table->foreignId('type_id')->nullable()->constrained('container_types')->onDelete('set null');
            $table->foreignId('status_id')->nullable()->constrained('container_statuses')->onDelete('set null');
            $table->foreignId('current_location_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Instruments
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->foreignId('manufacturer_id')->nullable()->constrained()->onDelete('set null');
            $table->string('model')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('instrument_categories')->onDelete('set null');
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_until')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('status_id')->nullable()->constrained('instrument_statuses')->onDelete('set null');
            $table->foreignId('current_container_id')->nullable()->constrained('containers')->onDelete('set null');
            $table->foreignId('current_location_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Defect Reports
        Schema::create('defect_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained()->onDelete('cascade');
            $table->foreignId('defect_type_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('reporting_department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->text('description');
            $table->enum('severity', ['niedrig', 'mittel', 'hoch', 'kritisch'])->default('mittel');
            $table->enum('status', ['offen', 'in_bearbeitung', 'abgeschlossen', 'abgelehnt'])->default('offen');
            $table->timestamp('reported_at')->useCurrent();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->decimal('repair_cost', 10, 2)->nullable();
            $table->timestamps();
        });

        // Purchase Orders
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('manufacturer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('defect_report_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('status_id')->nullable()->constrained('purchase_order_statuses')->onDelete('set null');
            $table->foreignId('ordered_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('order_date');
            $table->date('expected_delivery')->nullable();
            $table->date('delivery_date')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Instrument Movements
        Schema::create('instrument_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained()->onDelete('cascade');
            $table->enum('movement_type', ['location_change', 'container_assignment', 'container_removal', 'status_change', 'maintenance']);
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->foreignId('from_container_id')->nullable()->constrained('containers')->onDelete('set null');
            $table->foreignId('to_container_id')->nullable()->constrained('containers')->onDelete('set null');
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamp('performed_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrument_movements');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('defect_reports');
        Schema::dropIfExists('instruments');
        Schema::dropIfExists('containers');
    }
};
