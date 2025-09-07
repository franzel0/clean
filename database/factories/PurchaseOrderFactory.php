<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use App\Models\DefectReport;
use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    public function definition(): array
    {
        return [
            'defect_report_id' => DefectReport::factory(),
            'manufacturer_id' => Manufacturer::factory(),
            'ordered_by' => User::factory(),
            'order_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'expected_delivery' => $this->faker->dateTimeBetween('now', '+2 months'),
            'total_amount' => $this->faker->randomFloat(2, 50, 5000),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }

    public function delivered(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'delivery_date' => $this->faker->dateTimeBetween('-1 week', 'now'),
                'received_by' => User::factory(),
                'received_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            ];
        });
    }
}
