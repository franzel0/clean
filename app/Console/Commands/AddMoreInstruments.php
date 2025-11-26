<?php

namespace App\Console\Commands;

use App\Models\Instrument;
use App\Models\Container;
use App\Models\InstrumentCategory;
use App\Models\InstrumentStatus;
use App\Models\Manufacturer;
use App\Models\Department;
use Illuminate\Console\Command;

class AddMoreInstruments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instruments:add-more {count=80 : Number of instruments to add}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add more sample instruments to reach a total of 100';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $count = (int) $this->argument('count');
        
        $containers = Container::all();
        $categories = InstrumentCategory::all();
        $statuses = InstrumentStatus::all();
        $manufacturers = Manufacturer::all();
        $sterilDept = Department::where('code', 'STERIL')->first();

        if ($containers->isEmpty() || $categories->isEmpty() || $statuses->isEmpty() || $manufacturers->isEmpty()) {
            $this->error('Required base data not found. Please run seeders first.');
            return;
        }

        $instrumentNames = [
            'Chirurgische Schere Mayo',
            'Anatomische Pinzette',
            'Nadelhalter Standard',
            'Arterienklemme',
            'Wundhaken Small',
            'Wundhaken Medium',
            'Wundhaken Large',
            'Tuchklemme',
            'Spekulumspatel',
            'Raspatorium',
            'Elevatorium',
            'Periosteal',
            'Knochenhammer',
            'Beil Standard',
            'Stielende für Instrumente',
            'Mauls Retraktor',
            'Bauchdecken Sperre',
            'Beckenboden Sperre',
            'Nabel Sperre',
            'Peritoneum Sperre',
            'Bauchtuch Halter',
            'Instrumenten Ablage',
            'OP Besteck Halter',
            'Desinfektions Behälter',
            'Sterilisations Box',
            'Instrument Tablett',
            'Pinzette für Tupfer',
            'Fadenschneider',
            'Verbandschere',
            'Nahtmaterial Ständer',
        ];

        $baseInstCount = Instrument::count();
        $targetCount = 100;
        $toCreate = max(0, $targetCount - $baseInstCount);

        $this->info("Current instruments: {$baseInstCount}");
        $this->info("Target: 100");
        $this->info("Will create: {$toCreate} instruments");

        $bar = $this->output->createProgressBar($toCreate);
        $bar->start();

        for ($i = 0; $i < $toCreate; $i++) {
            $serialNumber = 'INS-' . str_pad($baseInstCount + $i + 1, 4, '0', STR_PAD_LEFT);
            $nameIndex = $i % count($instrumentNames);
            $name = $instrumentNames[$nameIndex];
            
            if ($i > 0) {
                $name .= ' (' . ($i + 1) . ')';
            }

            Instrument::create([
                'name' => $name,
                'serial_number' => $serialNumber,
                'manufacturer_id' => $manufacturers->random()->id,
                'model' => 'MODEL-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'category_id' => $categories->random()->id,
                'purchase_price' => rand(50, 500),
                'purchase_date' => now()->subMonths(rand(1, 12)),
                'warranty_until' => now()->addMonths(rand(6, 24)),
                'status_id' => $statuses->random()->id,
                'current_container_id' => $containers->random()->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
                'description' => 'Beispiel Instrument ' . ($baseInstCount + $i + 1),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Successfully added ' . $toCreate . ' instruments!');
        $this->info('Total instruments now: ' . Instrument::count());
    }
}
