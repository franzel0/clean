<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $medicalSuppliers = [
            'Aesculap AG',
            'Karl Storz SE & Co. KG',
            'Olympus Europa SE & Co. KG',
            'Medtronic Deutschland GmbH',
            'Johnson & Johnson Medical GmbH',
            'B. Braun Melsungen AG',
            'Stryker GmbH & Co. KG',
            'Zimmer Biomet Deutschland GmbH',
            'Cook Medical Deutschland GmbH',
            'Siemens Healthineers AG',
            'Philips Healthcare Deutschland GmbH',
            'Richard Wolf GmbH',
            'ERBE Elektromedizin GmbH',
            'Tuttlingen Medical Technology GmbH',
            'Surgical Instruments Tuttlingen'
        ];

        $companyName = $this->faker->randomElement($medicalSuppliers);
        
        return [
            'name' => $companyName,
            'contact_person' => $this->faker->name(),
            'email' => strtolower(str_replace([' ', '.', '&'], ['', '', ''], $companyName)) . '@' . $this->faker->safeEmailDomain(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress() . ', ' . $this->faker->postcode() . ' ' . $this->faker->city(),
            'website' => 'https://www.' . strtolower(str_replace([' ', '.', '&', 'ä', 'ö', 'ü'], ['', '', '', 'ae', 'oe', 'ue'], $companyName)) . '.de',
            'notes' => $this->faker->optional(0.3)->paragraph(),
            'is_active' => $this->faker->boolean(90), // 90% aktiv
        ];
    }
}
