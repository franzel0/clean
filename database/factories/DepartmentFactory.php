<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'code' => $this->faker->unique()->bothify('DEPT-##'),
            'location' => $this->faker->randomElement(['Erdgeschoss', 'Obergeschoss', 'Keller', 'Labor A', 'Labor B']),
            'description' => $this->faker->sentence(),
        ];
    }
}
