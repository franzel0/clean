<?php

namespace Database\Seeders;

use App\Models\Container;
use App\Models\Instrument;
use App\Models\DefectReport;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Department;
use App\Models\InstrumentCategory;
use App\Models\InstrumentStatus;
use App\Models\ContainerType;
use App\Models\ContainerStatus;
use App\Models\Manufacturer;
use App\Models\DefectType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Seed the application's sample data.
     * This includes containers, instruments, defect reports, and purchase orders.
     */
    public function run(): void
    {
        $this->createSampleUsers();
        $this->createSampleContainers();
        $this->createSampleInstruments();
        $this->createSampleDefectReports();
        $this->createSamplePurchaseOrders();
    }

    private function createSampleUsers(): void
    {
        $sterilDept = Department::where('code', 'STERIL')->first();
        $opDept = Department::where('code', 'OP')->first();
        $purchaseDept = Department::where('code', 'PURCHASE')->first();

        $users = [
            [
                'name' => 'Anna Sterilisation',
                'email' => 'steril@hospital.de',
                'password' => Hash::make('password'),
                'role' => 'sterilization_staff',
                'department_id' => $sterilDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. OP Personal',
                'email' => 'op@hospital.de',
                'password' => Hash::make('password'),
                'role' => 'or_staff',
                'department_id' => $opDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Einkauf Manager',
                'email' => 'purchase@hospital.de',
                'password' => Hash::make('password'),
                'role' => 'purchasing_staff',
                'department_id' => $purchaseDept->id,
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }

    private function createSampleContainers(): void
    {
        $containerTypes = ContainerType::all();
        $containerStatuses = ContainerStatus::all();
        $sterilDept = Department::where('code', 'STERIL')->first();
        $opDept = Department::where('code', 'OP')->first();

        $containers = [
            [
                'name' => 'Basis-Set Allgemeinchirurgie',
                'barcode' => 'BC001',
                'description' => 'Standard-Instrumentenset für allgemeine Eingriffe',
                'capacity' => 15,
                'type_id' => $containerTypes->where('name', 'Basis-Set')->first()->id,
                'status_id' => $containerStatuses->where('name', 'Steril')->first()->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Chirurgie-Set Laparoskopie',
                'barcode' => 'BC002',
                'description' => 'Spezielles Set für laparoskopische Eingriffe',
                'capacity' => 12,
                'type_id' => $containerTypes->where('name', 'Chirurgie-Set')->first()->id,
                'status_id' => $containerStatuses->where('name', 'In Aufbereitung')->first()->id,
                'current_location_id' => $opDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Spezial-Set Kardiochirurgie',
                'barcode' => 'BC003',
                'description' => 'Herzchirurgie-Instrumentenset',
                'capacity' => 20,
                'type_id' => $containerTypes->where('name', 'Spezial-Set')->first()->id,
                'status_id' => $containerStatuses->where('name', 'Steril')->first()->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Basis-Set Gefäßchirurgie',
                'barcode' => 'BC004',
                'description' => 'Instrumentenset für Gefäßoperationen',
                'capacity' => 18,
                'type_id' => $containerTypes->where('name', 'Basis-Set')->first()->id,
                'status_id' => $containerStatuses->where('name', 'In Aufbereitung')->first()->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Notfall-Set Trauma',
                'barcode' => 'BC005',
                'description' => 'Notfall-Instrumentarium für Traumachirurgie',
                'capacity' => 25,
                'type_id' => $containerTypes->where('name', 'Notfall-Set')->first()->id,
                'status_id' => $containerStatuses->where('name', 'Steril')->first()->id,
                'current_location_id' => $opDept->id,
                'is_active' => true,
            ],
        ];

        foreach ($containers as $container) {
            Container::create($container);
        }
    }

    private function createSampleInstruments(): void
    {
        $containers = Container::all();
        $categories = InstrumentCategory::all();
        $statuses = InstrumentStatus::all();
        $manufacturers = Manufacturer::all();
        $sterilDept = Department::where('code', 'STERIL')->first();

        // Array of common surgical instrument names
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

        // Create 100 instruments using factory
        Instrument::factory(100)->create([
            'status_id' => $statuses->random()->id,
            'current_container_id' => $containers->random()->id,
            'current_location_id' => $sterilDept->id,
            'is_active' => true,
        ])->each(function ($instrument, $index) use ($instrumentNames, $manufacturers, $categories) {
            $nameIndex = $index % count($instrumentNames);
            $name = $instrumentNames[$nameIndex];
            
            if ($index > 0 && $index % count($instrumentNames) === 0) {
                $name .= ' (' . ($index + 1) . ')';
            }

            $instrument->update([
                'name' => $name,
                'serial_number' => 'INS-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'manufacturer_id' => $manufacturers->random()->id,
                'category_id' => $categories->random()->id,
            ]);
        });
    }

    private function createSampleDefectReports(): void
    {
        $instruments = Instrument::all();
        $defectTypes = DefectType::all();
        $users = User::all();
        $opDept = Department::where('code', 'OP')->first();
        $sterilDept = Department::where('code', 'STERIL')->first();

        $defectReports = [
            [
                'instrument_id' => $instruments->where('serial_number', 'INS-0004')->first()?->id,
                'defect_type_id' => $defectTypes->where('name', 'Funktionsstörung')->first()->id,
                'reported_by' => $users->where('role', 'or_staff')->first()->id,
                'reporting_department_id' => $opDept->id,
                'description' => 'Klemme schließt nicht mehr richtig, Federung defekt',
                'severity' => 'mittel',
                'reported_at' => Carbon::now()->subDays(5),
                'status' => 'offen',
            ],
            [
                'instrument_id' => $instruments->where('serial_number', 'INS-0016')->first()?->id,
                'defect_type_id' => $defectTypes->where('name', 'Sicherheitsrisiko')->first()->id,
                'reported_by' => $users->where('role', 'or_staff')->first()->id,
                'reporting_department_id' => $opDept->id,
                'description' => 'Endoskop zeigt Bildausfälle, könnte während OP kritisch werden',
                'severity' => 'kritisch',
                'reported_at' => Carbon::now()->subDays(2),
                'status' => 'in_bearbeitung',
            ],
            [
                'instrument_id' => $instruments->where('serial_number', 'INS-0019')->first()?->id,
                'defect_type_id' => $defectTypes->where('name', 'Bruch/Riss')->first()->id,
                'reported_by' => $users->where('role', 'sterilization_staff')->first()->id,
                'reporting_department_id' => $sterilDept->id,
                'description' => 'Gehäuse hat Riss, möglicherweise durch Sturz entstanden',
                'severity' => 'hoch',
                'reported_at' => Carbon::now()->subDays(10),
                'status' => 'abgeschlossen',
            ],
        ];

        foreach ($defectReports as $report) {
            if ($report['instrument_id']) {
                DefectReport::create($report);
            }
        }
    }

    private function createSamplePurchaseOrders(): void
    {
        $manufacturers = Manufacturer::all();
        $users = User::all();
        $purchaseUser = $users->where('role', 'purchasing_staff')->first();
        $defectReports = DefectReport::all();

        // Get specific manufacturers by exact name
        $aesculap = $manufacturers->where('name', 'Aesculap AG')->first();
        $karlStorz = $manufacturers->where('name', 'Karl Storz SE & Co. KG')->first();
        $johnson = $manufacturers->where('name', 'Johnson & Johnson Medical')->first();

        $purchaseOrders = [
            [
                'order_number' => 'PO-2024-001',
                'defect_report_id' => $defectReports->first()->id,
                'supplier_id' => null,
                'manufacturer_id' => $aesculap->id,
                'ordered_by' => $purchaseUser->id,
                'order_date' => Carbon::now()->subDays(30),
                'expected_delivery' => Carbon::now()->subDays(15),
                'delivery_date' => Carbon::now()->subDays(10),
                'total_amount' => 2500.00,
                'notes' => 'Nachbestellung Standard-Instrumente',
                'received_at' => Carbon::now()->subDays(10),
                'received_by' => $purchaseUser->id,
            ],
            [
                'order_number' => 'PO-2024-002',
                'defect_report_id' => $defectReports->skip(1)->first()->id,
                'supplier_id' => null,
                'manufacturer_id' => $karlStorz->id,
                'ordered_by' => $purchaseUser->id,
                'order_date' => Carbon::now()->subDays(10),
                'expected_delivery' => Carbon::now()->addDays(5),
                'total_amount' => 15000.00,
                'notes' => 'Ersatz für defektes Endoskop',
            ],
            [
                'order_number' => 'PO-2024-003',
                'defect_report_id' => $defectReports->skip(2)->first()->id,
                'supplier_id' => null,
                'manufacturer_id' => $johnson->id,
                'ordered_by' => $purchaseUser->id,
                'order_date' => Carbon::now()->subDays(3),
                'expected_delivery' => Carbon::now()->addDays(14),
                'total_amount' => 8750.00,
                'notes' => 'Erweiterung Spezialinstrumente',
            ],
        ];

        foreach ($purchaseOrders as $order) {
            PurchaseOrder::create($order);
        }
    }
}
