<?php

namespace Database\Seeders;

use App\Models\InstrumentStatus;
use Illuminate\Database\Seeder;

class InstrumentStatusSeeder extends Seeder
{
    /**
     * Seed the instrument statuses table.
     */
    public function run(): void
    {
        // Instrument Status (diese müssen mit InstrumentStatusContextSeeder übereinstimmen)
        $instrumentStatuses = [
            ['name' => 'Verfügbar', 'description' => 'Instrument ist verfügbar', 'color' => 'green', 'sort_order' => 1, 'is_active' => true],
            ['name' => 'Im Einsatz', 'description' => 'Instrument wird verwendet', 'color' => 'blue', 'sort_order' => 2, 'is_active' => true],
            ['name' => 'In Betrieb', 'description' => 'Instrument ist in Betrieb', 'color' => 'blue', 'sort_order' => 3, 'is_active' => true],
            ['name' => 'In Aufbereitung', 'description' => 'Instrument wird aufbereitet', 'color' => 'yellow', 'sort_order' => 4, 'is_active' => true],
            ['name' => 'In Wartung', 'description' => 'Instrument wird gewartet', 'color' => 'orange', 'sort_order' => 5, 'is_active' => true],
            ['name' => 'Defekt', 'description' => 'Instrument ist defekt', 'color' => 'red', 'sort_order' => 6, 'is_active' => true],
            ['name' => 'Defekt gemeldet', 'description' => 'Defekt wurde gemeldet', 'color' => 'red', 'sort_order' => 7, 'is_active' => true],
            ['name' => 'Defekt bestätigt', 'description' => 'Defekt wurde bestätigt', 'color' => 'red', 'sort_order' => 8, 'is_active' => true],
            ['name' => 'In Reparatur', 'description' => 'Instrument ist in Reparatur', 'color' => 'orange', 'sort_order' => 9, 'is_active' => true],
            ['name' => 'Repariert', 'description' => 'Instrument wurde repariert', 'color' => 'green', 'sort_order' => 10, 'is_active' => true],
            ['name' => 'Ersatz bestellt', 'description' => 'Ersatz wurde bestellt', 'color' => 'purple', 'sort_order' => 11, 'is_active' => true],
            ['name' => 'Ersatz geliefert', 'description' => 'Ersatz wurde geliefert', 'color' => 'green', 'sort_order' => 12, 'is_active' => true],
            ['name' => 'Außer Betrieb', 'description' => 'Instrument ist außer Betrieb', 'color' => 'gray', 'sort_order' => 13, 'is_active' => true],
            ['name' => 'Verloren/Vermisst', 'description' => 'Instrument ist verloren', 'color' => 'gray', 'sort_order' => 14, 'is_active' => true],
            ['name' => 'Aussortiert', 'description' => 'Instrument ist aussortiert', 'color' => 'gray', 'sort_order' => 15, 'is_active' => true],
        ];

        foreach ($instrumentStatuses as $status) {
            InstrumentStatus::firstOrCreate(
                ['name' => $status['name']], 
                $status
            );
        }
    }
}
