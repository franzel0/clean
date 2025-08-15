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
        // Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->unique();
            $table->string('location');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Operating Rooms
        Schema::create('operating_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->unique();
            $table->string('location');
            $table->text('description')->nullable();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Instrument Categories
        Schema::create('instrument_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Instrument Statuses
        Schema::create('instrument_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color', 7)->default('#6B7280');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Container Types
        Schema::create('container_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Container Statuses
        Schema::create('container_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color', 7)->default('#6B7280');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Defect Types
        Schema::create('defect_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('severity', ['niedrig', 'mittel', 'hoch', 'kritisch'])->default('mittel');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Purchase Order Statuses
        Schema::create('purchase_order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color', 7)->default('#6B7280');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Manufacturers
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('website')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturers');
        Schema::dropIfExists('purchase_order_statuses');
        Schema::dropIfExists('defect_types');
        Schema::dropIfExists('container_statuses');
        Schema::dropIfExists('container_types');
        Schema::dropIfExists('instrument_statuses');
        Schema::dropIfExists('instrument_categories');
        Schema::dropIfExists('operating_rooms');
        Schema::dropIfExists('departments');
    }
};
