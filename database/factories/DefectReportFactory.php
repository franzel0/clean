<?php

namespace Database\Factories;

use App\Models\DefectReport;
use App\Models\Instrument;
use App\Models\User;
use App\Models\Department;
use App\Models\DefectType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DefectReportFactory extends Factory
{
    protected $model = DefectReport::class;

    public function definition(): array
    {
        return [
            'instrument_id' => Instrument::factory(),
            'reported_by' => User::factory(),
            'reporting_department_id' => Department::factory(),
            'operating_room_id' => null,
            'defect_type_id' => DefectType::factory(),
            'description' => $this->faker->paragraph(),
            'severity' => $this->faker->randomElement(['niedrig', 'mittel', 'hoch', 'kritisch']),
            'is_completed' => false,
            'reported_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'photos' => [],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
