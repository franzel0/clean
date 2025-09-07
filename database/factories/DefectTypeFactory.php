<?php

namespace Database\Factories;

use App\Models\DefectType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DefectTypeFactory extends Factory
{
    protected $model = DefectType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'severity' => $this->faker->randomElement(['niedrig', 'mittel', 'hoch', 'kritisch']),
            'description' => $this->faker->sentence(),
            'sort_order' => $this->faker->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
}
