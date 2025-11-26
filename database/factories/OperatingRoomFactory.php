<?php

namespace Database\Factories;

use App\Models\OperatingRoom;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperatingRoomFactory extends Factory
{
    protected $model = OperatingRoom::class;

    public function definition(): array
    {
        return [
            'name' => 'OP ' . $this->faker->numberBetween(1, 20),
            'code' => $this->faker->unique()->bothify('OP-###'),
            'department_id' => Department::factory(),
            'location' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
