<?php

namespace Database\Factories;

use App\Models\InstrumentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstrumentStatusFactory extends Factory
{
    protected $model = InstrumentStatus::class;

    public function definition(): array
    {
        static $counter = 0;
        $counter++;
        
        return [
            'name' => fake()->words(2, true) . ' status ' . $counter . ' ' . microtime(true),
            'description' => fake()->optional()->sentence(),
            'color' => fake()->hexColor(),
            'bg_class' => 'bg-gray-100',
            'text_class' => 'text-gray-800',
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 100),
            'available_in_purchase_orders' => fake()->boolean(30),
            'available_in_defect_reports' => fake()->boolean(40),
            'available_in_instruments' => fake()->boolean(80),
            'available_in_containers' => fake()->boolean(20),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function forPurchaseOrders(): static
    {
        return $this->state(fn (array $attributes) => [
            'available_in_purchase_orders' => true,
        ]);
    }

    public function forDefectReports(): static
    {
        return $this->state(fn (array $attributes) => [
            'available_in_defect_reports' => true,
        ]);
    }

    public function forInstruments(): static
    {
        return $this->state(fn (array $attributes) => [
            'available_in_instruments' => true,
        ]);
    }

    public function forContainers(): static
    {
        return $this->state(fn (array $attributes) => [
            'available_in_containers' => true,
        ]);
    }
}
