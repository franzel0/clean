<?php

namespace Database\Factories;

use App\Models\InstrumentMovement;
use App\Models\Instrument;
use App\Models\User;
use App\Models\Container;
use App\Models\InstrumentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstrumentMovementFactory extends Factory
{
    protected $model = InstrumentMovement::class;

    public function definition(): array
    {
        $movementTypes = ['location_change', 'container_assignment', 'container_removal', 'status_change', 'maintenance'];
        
        return [
            'instrument_id' => Instrument::factory(),
            'from_container_id' => null,
            'to_container_id' => null,
            'movement_type' => $this->faker->randomElement($movementTypes),
            'from_location' => null,
            'to_location' => null,
            'from_status' => null,
            'to_status' => null,
            'performed_by' => User::factory(),
            'notes' => $this->faker->optional()->sentence(),
            'performed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
