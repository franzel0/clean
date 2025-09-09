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
        Schema::create('container_statistics_settings', function (Blueprint $table) {
            $table->id();
            $table->string('card_name'); // 'card_1', 'card_2', 'card_3', 'card_4'
            $table->foreignId('instrument_status_id')->nullable()->constrained('instrument_statuses')->onDelete('set null');
            $table->string('display_name'); // Custom name for the card
            $table->string('color')->default('blue'); // Card color theme
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique('card_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_statistics_settings');
    }
};
