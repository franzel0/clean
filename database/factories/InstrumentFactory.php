<?php

namespace Database\Factories;

use App\Models\Instrument;
use App\Models\InstrumentCategory;
use App\Models\Manufacturer;
use App\Models\InstrumentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstrumentFactory extends Factory
{
    protected $model = Instrument::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'serial_number' => $this->faker->unique()->bothify('??###-###'),
            'model' => $this->faker->bothify('Model-###'),
            'category_id' => InstrumentCategory::factory(),
            'manufacturer_id' => Manufacturer::factory(),
            'status_id' => function () {
                // Dynamisch: Nimm eine zufällige existierende Status-ID
                return \App\Models\InstrumentStatus::inRandomOrder()->first()?->id ?? 1;
            },
            'purchase_price' => $this->faker->randomFloat(2, 100, 10000),
            'purchase_date' => $this->faker->dateTimeBetween('-2 years', '-1 month'),
            'warranty_until' => $this->faker->dateTimeBetween('now', '+2 years'),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
