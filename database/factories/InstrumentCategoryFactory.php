<?php

namespace Database\Factories;

use App\Models\InstrumentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstrumentCategoryFactory extends Factory
{
    protected $model = InstrumentCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'sort_order' => $this->faker->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
}
