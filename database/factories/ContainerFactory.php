<?php

namespace Database\Factories;

use App\Models\Container;
use App\Models\ContainerType;
use App\Models\ContainerStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContainerFactory extends Factory
{
    protected $model = Container::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'barcode' => $this->faker->unique()->bothify('BC####'),
            'type_id' => ContainerType::factory(),
            'status_id' => ContainerStatus::factory(),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
