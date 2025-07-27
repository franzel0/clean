<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('category'); // 'scissors', 'forceps', 'scalpel', 'clamp', etc.
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_until')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('available'); // available, in_use, defective, in_repair, out_of_service
            $table->foreignId('current_container_id')->nullable()->constrained('containers');
            $table->foreignId('current_location_id')->nullable()->constrained('departments');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instruments');
    }
};
