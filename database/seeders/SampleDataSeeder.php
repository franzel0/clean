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
use App\Models\PurchaseOrderStatus;
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
        $opDept = Department::where('code', 'OP')->first();

        // Get specific manufacturers by exact name
        $aesculap = $manufacturers->where('name', 'Aesculap AG')->first();
        $karlStorz = $manufacturers->where('name', 'Karl Storz SE & Co. KG')->first();
        $bbraun = $manufacturers->where('name', 'B. Braun Melsungen AG')->first();
        $johnson = $manufacturers->where('name', 'Johnson & Johnson Medical')->first();

        $instruments = [
            // Container 1: Basis-Set Allgemeinchirurgie
            [
                'name' => 'Chirurgische Schere Mayo',
                'serial_number' => 'INS-001',
                'manufacturer_id' => $aesculap->id,
                'model' => 'BC123',
                'category_id' => $categories->where('name', 'Scheren')->first()->id,
                'purchase_price' => 89.50,
                'purchase_date' => '2024-01-15',
                'warranty_until' => '2026-01-15',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => $containers[0]->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
                'description' => 'Hochwertige chirurgische Schere für präzise Schnitte',
            ],
            [
                'name' => 'Anatomische Pinzette',
                'serial_number' => 'INS-002',
                'manufacturer_id' => $aesculap->id,
                'model' => 'BD456',
                'category_id' => $categories->where('name', 'Pinzetten')->first()->id,
                'purchase_price' => 45.00,
                'purchase_date' => '2024-02-10',
                'warranty_until' => '2026-02-10',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => $containers[0]->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Nadelhalter Standard',
                'serial_number' => 'INS-003',
                'manufacturer_id' => $karlStorz->id,
                'model' => 'NH789',
                'category_id' => $categories->where('name', 'Nadelhalter')->first()->id,
                'purchase_price' => 125.00,
                'purchase_date' => '2024-03-05',
                'warranty_until' => '2026-03-05',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => $containers[0]->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Arterienklemme',
                'serial_number' => 'INS-004',
                'manufacturer_id' => $bbraun->id,
                'model' => 'AK321',
                'category_id' => $categories->where('name', 'Klemmen')->first()->id,
                'purchase_price' => 78.00,
                'purchase_date' => '2024-01-20',
                'warranty_until' => '2026-01-20',
                'status_id' => $statuses->where('name', 'Defekt')->first()->id,
                'current_container_id' => $containers[0]->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Wundhaken Small',
                'serial_number' => 'INS-005',
                'manufacturer_id' => $johnson->id,
                'model' => 'WH102',
                'category_id' => $categories->where('name', 'Retraktor')->first()->id,
                'purchase_price' => 65.00,
                'purchase_date' => '2024-02-15',
                'warranty_until' => '2026-02-15',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => $containers[0]->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],

            // Container 2: Laparoskopie-Set
            [
                'name' => 'Laparoskopische Schere',
                'serial_number' => 'INS-006',
                'manufacturer_id' => $karlStorz->id,
                'model' => 'LS456',
                'category_id' => $categories->where('name', 'Endoskopie')->first()->id,
                'purchase_price' => 1250.00,
                'purchase_date' => '2024-03-10',
                'warranty_until' => '2026-03-10',
                'status_id' => $statuses->where('name', 'Im Einsatz')->first()->id,
                'current_container_id' => $containers[1]->id,
                'current_location_id' => $opDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Trokar 5mm',
                'serial_number' => 'INS-007',
                'manufacturer_id' => $karlStorz->id,
                'model' => 'TR505',
                'category_id' => $categories->where('name', 'Endoskopie')->first()->id,
                'purchase_price' => 890.00,
                'purchase_date' => '2024-03-12',
                'warranty_until' => '2026-03-12',
                'status_id' => $statuses->where('name', 'Im Einsatz')->first()->id,
                'current_container_id' => $containers[1]->id,
                'current_location_id' => $opDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Laparoskopische Pinzette',
                'serial_number' => 'INS-008',
                'manufacturer_id' => $karlStorz->id,
                'model' => 'LP789',
                'category_id' => $categories->where('name', 'Endoskopie')->first()->id,
                'purchase_price' => 750.00,
                'purchase_date' => '2024-03-15',
                'warranty_until' => '2026-03-15',
                'status_id' => $statuses->where('name', 'Im Einsatz')->first()->id,
                'current_container_id' => $containers[1]->id,
                'current_location_id' => $opDept->id,
                'is_active' => true,
            ],

            // Container 3: Kardiochirurgie
            [
                'name' => 'Herzklappenspreizer',
                'serial_number' => 'INS-009',
                'manufacturer_id' => $aesculap->id,
                'model' => 'HK890',
                'category_id' => $categories->where('name', 'Spezialinstrumente')->first()->id,
                'purchase_price' => 2500.00,
                'purchase_date' => '2024-04-01',
                'warranty_until' => '2027-04-01',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => $containers[2]->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Gefäßklemme Satinsky',
                'serial_number' => 'INS-010',
                'manufacturer_id' => $bbraun->id,
                'model' => 'GK567',
                'category_id' => $categories->where('name', 'Klemmen')->first()->id,
                'purchase_price' => 380.00,
                'purchase_date' => '2024-04-05',
                'warranty_until' => '2026-04-05',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => $containers[2]->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],

            // Container 4: Gefäßchirurgie
            [
                'name' => 'Gefäßschere Potts',
                'serial_number' => 'INS-011',
                'manufacturer_id' => $aesculap->id,
                'model' => 'GP234',
                'category_id' => $categories->where('name', 'Scheren')->first()->id,
                'purchase_price' => 215.00,
                'purchase_date' => '2024-02-20',
                'warranty_until' => '2026-02-20',
                'status_id' => $statuses->where('name', 'In Aufbereitung')->first()->id,
                'current_container_id' => $containers[3]->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Gefäßpinzette DeBakey',
                'serial_number' => 'INS-012',
                'manufacturer_id' => $johnson->id,
                'model' => 'DB345',
                'category_id' => $categories->where('name', 'Pinzetten')->first()->id,
                'purchase_price' => 95.00,
                'purchase_date' => '2024-02-25',
                'warranty_until' => '2026-02-25',
                'status_id' => $statuses->where('name', 'In Aufbereitung')->first()->id,
                'current_container_id' => $containers[3]->id,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],

            // Container 5: Notfall-Set
            [
                'name' => 'Trauma-Schere',
                'serial_number' => 'INS-013',
                'manufacturer_id' => $aesculap->id,
                'model' => 'TS678',
                'category_id' => $categories->where('name', 'Scheren')->first()->id,
                'purchase_price' => 145.00,
                'purchase_date' => '2024-05-01',
                'warranty_until' => '2026-05-01',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => $containers[4]->id,
                'current_location_id' => $opDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Skalpellgriff Nr. 4',
                'serial_number' => 'INS-014',
                'manufacturer_id' => $bbraun->id,
                'model' => 'SG404',
                'category_id' => $categories->where('name', 'Skalpelle')->first()->id,
                'purchase_price' => 35.00,
                'purchase_date' => '2024-05-05',
                'warranty_until' => '2026-05-05',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => $containers[4]->id,
                'current_location_id' => $opDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Knochenzange',
                'serial_number' => 'INS-015',
                'manufacturer_id' => $johnson->id,
                'model' => 'KZ789',
                'category_id' => $categories->where('name', 'Spezialinstrumente')->first()->id,
                'purchase_price' => 420.00,
                'purchase_date' => '2024-05-10',
                'warranty_until' => '2026-05-10',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => $containers[4]->id,
                'current_location_id' => $opDept->id,
                'is_active' => true,
            ],

            // Einige Instrumente ohne Container
            [
                'name' => 'Endoskop Laparoskopie',
                'serial_number' => 'INS-016',
                'manufacturer_id' => $karlStorz->id,
                'model' => 'EL301',
                'category_id' => $categories->where('name', 'Endoskopie')->first()->id,
                'purchase_price' => 15000.00,
                'purchase_date' => '2024-06-01',
                'warranty_until' => '2027-06-01',
                'status_id' => $statuses->where('name', 'In Reparatur')->first()->id,
                'current_container_id' => null,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
                'description' => 'Hochauflösendes Laparoskop mit LED-Beleuchtung',
            ],
            [
                'name' => 'Mikrochirurgie-Pinzette',
                'serial_number' => 'INS-017',
                'manufacturer_id' => $aesculap->id,
                'model' => 'MP123',
                'category_id' => $categories->where('name', 'Pinzetten')->first()->id,
                'purchase_price' => 280.00,
                'purchase_date' => '2024-06-15',
                'warranty_until' => '2026-06-15',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => null,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Biopsiezange',
                'serial_number' => 'INS-018',
                'manufacturer_id' => $karlStorz->id,
                'model' => 'BZ456',
                'category_id' => $categories->where('name', 'Endoskopie')->first()->id,
                'purchase_price' => 650.00,
                'purchase_date' => '2024-07-01',
                'warranty_until' => '2026-07-01',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => null,
                'current_location_id' => $sterilDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Ultraschall-Dissector',
                'serial_number' => 'INS-019',
                'manufacturer_id' => $johnson->id,
                'model' => 'UD789',
                'category_id' => $categories->where('name', 'Spezialinstrumente')->first()->id,
                'purchase_price' => 8500.00,
                'purchase_date' => '2024-07-15',
                'warranty_until' => '2027-07-15',
                'status_id' => $statuses->where('name', 'Außer Betrieb')->first()->id,
                'current_container_id' => null,
                'current_location_id' => $sterilDept->id,
                'is_active' => false,
                'description' => 'Ultraschallgerät für präzise Gewebedissektion',
            ],
            [
                'name' => 'Operationsmikroskop',
                'serial_number' => 'INS-020',
                'manufacturer_id' => $karlStorz->id,
                'model' => 'OM901',
                'category_id' => $categories->where('name', 'Spezialinstrumente')->first()->id,
                'purchase_price' => 45000.00,
                'purchase_date' => '2024-08-01',
                'warranty_until' => '2029-08-01',
                'status_id' => $statuses->where('name', 'Verfügbar')->first()->id,
                'current_container_id' => null,
                'current_location_id' => $opDept->id,
                'is_active' => true,
                'description' => 'Hochpräzises Operationsmikroskop für Mikrochirurgie',
            ],
        ];

        foreach ($instruments as $instrument) {
            Instrument::create($instrument);
        }
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
                'instrument_id' => $instruments->where('serial_number', 'INS-004')->first()->id,
                'defect_type_id' => $defectTypes->where('name', 'Funktionsstörung')->first()->id,
                'reported_by' => $users->where('role', 'or_staff')->first()->id,
                'reporting_department_id' => $opDept->id,
                'description' => 'Klemme schließt nicht mehr richtig, Federung defekt',
                'severity' => 'mittel',
                'reported_at' => Carbon::now()->subDays(5),
                'status' => 'offen',
            ],
            [
                'instrument_id' => $instruments->where('serial_number', 'INS-016')->first()->id,
                'defect_type_id' => $defectTypes->where('name', 'Sicherheitsrisiko')->first()->id,
                'reported_by' => $users->where('role', 'or_staff')->first()->id,
                'reporting_department_id' => $opDept->id,
                'description' => 'Endoskop zeigt Bildausfälle, könnte während OP kritisch werden',
                'severity' => 'kritisch',
                'reported_at' => Carbon::now()->subDays(2),
                'status' => 'in_bearbeitung',
            ],
            [
                'instrument_id' => $instruments->where('serial_number', 'INS-019')->first()->id,
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
            DefectReport::create($report);
        }
    }

    private function createSamplePurchaseOrders(): void
    {
        $manufacturers = Manufacturer::all();
        $statuses = PurchaseOrderStatus::all();
        $users = User::all();
        $purchaseUser = $users->where('role', 'purchasing_staff')->first();

        // Get specific manufacturers by exact name
        $aesculap = $manufacturers->where('name', 'Aesculap AG')->first();
        $karlStorz = $manufacturers->where('name', 'Karl Storz SE & Co. KG')->first();
        $johnson = $manufacturers->where('name', 'Johnson & Johnson Medical')->first();

        $purchaseOrders = [
            [
                'order_number' => 'PO-2024-001',
                'supplier_id' => null,
                'manufacturer_id' => $aesculap->id,
                'status_id' => $statuses->where('name', 'Geliefert')->first()->id,
                'ordered_by' => $purchaseUser->id,
                'order_date' => Carbon::now()->subDays(30),
                'expected_delivery' => Carbon::now()->subDays(15),
                'delivery_date' => Carbon::now()->subDays(10),
                'total_amount' => 2500.00,
                'notes' => 'Nachbestellung Standard-Instrumente',
            ],
            [
                'order_number' => 'PO-2024-002',
                'supplier_id' => null,
                'manufacturer_id' => $karlStorz->id,
                'status_id' => $statuses->where('name', 'Bestellt')->first()->id,
                'ordered_by' => $purchaseUser->id,
                'order_date' => Carbon::now()->subDays(10),
                'expected_delivery' => Carbon::now()->addDays(5),
                'total_amount' => 15000.00,
                'notes' => 'Ersatz für defektes Endoskop',
            ],
            [
                'order_number' => 'PO-2024-003',
                'supplier_id' => null,
                'manufacturer_id' => $johnson->id,
                'status_id' => $statuses->where('name', 'Freigegeben')->first()->id,
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
