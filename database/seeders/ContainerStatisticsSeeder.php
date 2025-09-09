<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContainerStatisticsSetting;
use App\Models\InstrumentStatus;

class ContainerStatisticsSeeder extends Seeder
{
    public function run(): void
    {
        // Default-Settings mit echten Status-IDs
        $statusMappings = [
            'Verfügbar' => 'green',
            'In Wartung' => 'yellow', 
            'Defekt gemeldet' => 'red',
            'Außer Betrieb' => 'gray'
        ];

        $sortOrder = 1;
        foreach ($statusMappings as $statusName => $color) {
            $status = InstrumentStatus::where('name', $statusName)->first();
            
            ContainerStatisticsSetting::create([
                'card_name' => 'card_' . $sortOrder,
                'instrument_status_id' => $status?->id,
                'display_name' => $statusName,
                'color' => $color,
                'is_active' => true,
                'sort_order' => $sortOrder
            ]);
            
            $sortOrder++;
        }
    }
}
