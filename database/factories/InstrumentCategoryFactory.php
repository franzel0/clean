<?php

namespace Database\Factories;

use App\Models\InstrumentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstrumentCategoryFactory extends Factory
{
    protected $model = InstrumentCategory::class;

    public function definition(): array
    {
        static $counter = 0;
        $counter++;
        
        return [
            'name' => 'Category ' . $counter . ' ' . $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
            'sort_order' => $this->faker->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
}
