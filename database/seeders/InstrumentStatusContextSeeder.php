<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InstrumentStatus;

class InstrumentStatusContextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definiere, welche Status in welchen Kontexten verfügbar sind
        $statusContexts = [
            'Verfügbar' => [
                'available_in_purchase_orders' => false,
                'available_in_defect_reports' => false,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'In Betrieb' => [
                'available_in_purchase_orders' => false,
                'available_in_defect_reports' => false,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'Defekt gemeldet' => [
                'available_in_purchase_orders' => false,
                'available_in_defect_reports' => true,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'Defekt bestätigt' => [
                'available_in_purchase_orders' => false,
                'available_in_defect_reports' => true,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'Ersatz bestellt' => [
                'available_in_purchase_orders' => true,
                'available_in_defect_reports' => true,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'Ersatz geliefert' => [
                'available_in_purchase_orders' => true,
                'available_in_defect_reports' => true,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'In Reparatur' => [
                'available_in_purchase_orders' => true,
                'available_in_defect_reports' => true,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'Repariert' => [
                'available_in_purchase_orders' => true,
                'available_in_defect_reports' => true,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'Aussortiert' => [
                'available_in_purchase_orders' => false,
                'available_in_defect_reports' => false,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'Verloren/Vermisst' => [
                'available_in_purchase_orders' => false,
                'available_in_defect_reports' => false,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'In Wartung' => [
                'available_in_purchase_orders' => false,
                'available_in_defect_reports' => false,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'Im Einsatz' => [
                'available_in_purchase_orders' => false,
                'available_in_defect_reports' => false,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'In Aufbereitung' => [
                'available_in_purchase_orders' => false,
                'available_in_defect_reports' => false,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'Defekt' => [
                'available_in_purchase_orders' => false,
                'available_in_defect_reports' => true,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
            'Außer Betrieb' => [
                'available_in_purchase_orders' => false,
                'available_in_defect_reports' => false,
                'available_in_instruments' => true,
                'available_in_containers' => false,
            ],
        ];

        foreach ($statusContexts as $statusName => $contexts) {
            $status = InstrumentStatus::where('name', $statusName)->first();
            if ($status) {
                $status->update($contexts);
                $this->command->info("Updated context availability for status: {$statusName}");
            } else {
                $this->command->warn("Status not found: {$statusName}");
            }
        }
    }
}
