<?php

namespace Database\Factories;

use App\Models\ContainerType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContainerTypeFactory extends Factory
{
    protected $model = ContainerType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
