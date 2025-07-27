<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrument_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained('instruments');
            $table->foreignId('from_department_id')->nullable()->constrained('departments');
            $table->foreignId('to_department_id')->nullable()->constrained('departments');
            $table->foreignId('from_container_id')->nullable()->constrained('containers');
            $table->foreignId('to_container_id')->nullable()->constrained('containers');
            $table->string('movement_type'); // 'dispatch', 'return', 'transfer', 'sterilization', 'repair'
            $table->string('status_before');
            $table->string('status_after');
            $table->foreignId('moved_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamp('moved_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrument_movements');
    }
};
