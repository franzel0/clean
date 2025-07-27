<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('defect_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->foreignId('instrument_id')->constrained('instruments');
            $table->foreignId('reported_by')->constrained('users');
            $table->foreignId('reporting_department_id')->constrained('departments');
            $table->foreignId('operating_room_id')->nullable()->constrained('operating_rooms');
            $table->string('defect_type'); // 'broken', 'dull', 'bent', 'missing_parts', 'other'
            $table->text('description');
            $table->string('severity')->default('medium'); // low, medium, high, critical
            $table->string('status')->default('reported'); // reported, acknowledged, in_review, ordered, received, repaired, closed
            $table->timestamp('reported_at');
            $table->timestamp('acknowledged_at')->nullable();
            $table->foreignId('acknowledged_by')->nullable()->constrained('users');
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->text('resolution_notes')->nullable();
            $table->json('photos')->nullable(); // Store photo URLs
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('defect_reports');
    }
};
