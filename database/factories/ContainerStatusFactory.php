<?php

namespace Database\Factories;

use App\Models\ContainerStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContainerStatusFactory extends Factory
{
    protected $model = ContainerStatus::class;

    public function definition(): array
    {
        static $counter = 0;
        $counter++;
        
        return [
            'name' => $this->faker->words(2, true) . ' cstatus ' . $counter . ' ' . microtime(true),
            'description' => $this->faker->sentence(),
            'color' => $this->faker->hexColor(),
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
