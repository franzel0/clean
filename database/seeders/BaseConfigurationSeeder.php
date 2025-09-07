<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\OperatingRoom;
use App\Models\InstrumentCategory;
use App\Models\InstrumentStatus;
use App\Models\ContainerType;
use App\Models\ContainerStatus;
use App\Models\DefectType;
use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BaseConfigurationSeeder extends Seeder
{
    /**
     * Seed the application's basic configuration data.
     * This includes categories, statuses, manufacturers, and essential users.
     */
    public function run(): void
    {
        $this->createInstrumentCategories();
        $this->createInstrumentStatuses();
        $this->createContainerTypes();
        $this->createContainerStatuses();
        $this->createDefectTypes();
        $this->createManufacturers();
        $this->createDepartments();
        $this->createOperatingRooms();
        $this->createAdminUser();
    }

    private function createInstrumentCategories(): void
    {
        $categories = [
            ['name' => 'Scheren', 'description' => 'Chirurgische Scheren', 'sort_order' => 10, 'is_active' => true],
            ['name' => 'Pinzetten', 'description' => 'Anatomische und chirurgische Pinzetten', 'sort_order' => 20, 'is_active' => true],
            ['name' => 'Nadelhalter', 'description' => 'Nadelhalter verschiedener Größen', 'sort_order' => 30, 'is_active' => true],
            ['name' => 'Klemmen', 'description' => 'Arterienklemmen und Gewebezangen', 'sort_order' => 40, 'is_active' => true],
            ['name' => 'Retraktor', 'description' => 'Wundhaken und Spreizer', 'sort_order' => 50, 'is_active' => true],
            ['name' => 'Skalpelle', 'description' => 'Skalpellgriffe und Klingen', 'sort_order' => 60, 'is_active' => true],
            ['name' => 'Endoskopie', 'description' => 'Endoskopische Instrumente', 'sort_order' => 70, 'is_active' => true],
            ['name' => 'Spezialinstrumente', 'description' => 'Fachspezifische Instrumente', 'sort_order' => 80, 'is_active' => true],
        ];

        foreach ($categories as $category) {
            InstrumentCategory::create($category);
        }
    }

    private function createInstrumentStatuses(): void
    {
        $statuses = [
            ['name' => 'Verfügbar', 'color' => '#10B981', 'sort_order' => 10, 'is_active' => true],
            ['name' => 'Im Einsatz', 'color' => '#F59E0B', 'sort_order' => 20, 'is_active' => true],
            ['name' => 'In Aufbereitung', 'color' => '#3B82F6', 'sort_order' => 30, 'is_active' => true],
            ['name' => 'Defekt', 'color' => '#EF4444', 'sort_order' => 40, 'is_active' => true],
            ['name' => 'In Reparatur', 'color' => '#F97316', 'sort_order' => 50, 'is_active' => true],
            ['name' => 'Außer Betrieb', 'color' => '#6B7280', 'sort_order' => 60, 'is_active' => true],
        ];

        foreach ($statuses as $status) {
            InstrumentStatus::firstOrCreate(
                ['name' => $status['name']], 
                $status
            );
        }
    }

    private function createContainerTypes(): void
    {
        $types = [
            ['name' => 'Basis-Set', 'description' => 'Standard-Instrumentenset', 'sort_order' => 10, 'is_active' => true],
            ['name' => 'Chirurgie-Set', 'description' => 'Spezielle Operationssets', 'sort_order' => 20, 'is_active' => true],
            ['name' => 'Spezial-Set', 'description' => 'Fachspezifische Instrumentensets', 'sort_order' => 30, 'is_active' => true],
            ['name' => 'Notfall-Set', 'description' => 'Notfall-Instrumentarium', 'sort_order' => 40, 'is_active' => true],
        ];

        foreach ($types as $type) {
            ContainerType::create($type);
        }
    }

    private function createContainerStatuses(): void
    {
        $statuses = [
            ['name' => 'Steril', 'color' => '#10B981', 'sort_order' => 10, 'is_active' => true],
            ['name' => 'Unsteril', 'color' => '#EF4444', 'sort_order' => 20, 'is_active' => true],
            ['name' => 'In Aufbereitung', 'color' => '#F59E0B', 'sort_order' => 30, 'is_active' => true],
            ['name' => 'Wartung', 'color' => '#6B7280', 'sort_order' => 40, 'is_active' => true],
        ];

        foreach ($statuses as $status) {
            ContainerStatus::create($status);
        }
    }

    private function createDefectTypes(): void
    {
        $types = [
            ['name' => 'Verschleiß', 'severity' => 'niedrig', 'description' => 'Normale Abnutzung', 'sort_order' => 10, 'is_active' => true],
            ['name' => 'Funktionsstörung', 'severity' => 'mittel', 'description' => 'Eingeschränkte Funktionalität', 'sort_order' => 20, 'is_active' => true],
            ['name' => 'Bruch/Riss', 'severity' => 'hoch', 'description' => 'Physische Beschädigung', 'sort_order' => 30, 'is_active' => true],
            ['name' => 'Korrosion', 'severity' => 'mittel', 'description' => 'Rostbildung oder Korrosion', 'sort_order' => 40, 'is_active' => true],
            ['name' => 'Sicherheitsrisiko', 'severity' => 'kritisch', 'description' => 'Gefährdung für Patienten/Personal', 'sort_order' => 50, 'is_active' => true],
        ];

        foreach ($types as $type) {
            DefectType::create($type);
        }
    }

    private function createManufacturers(): void
    {
        $manufacturers = [
            [
                'name' => 'Aesculap AG',
                'website' => 'https://www.aesculap.com',
                'contact_person' => 'Service Team',
                'contact_email' => 'service@aesculap.com',
                'contact_phone' => '+49 7461 95-0',
                'description' => 'Führender Anbieter von chirurgischen Instrumenten',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Karl Storz SE & Co. KG',
                'website' => 'https://www.karlstorz.com',
                'contact_person' => 'Kundenservice',
                'contact_email' => 'info@karlstorz.com',
                'contact_phone' => '+49 7461 708-0',
                'description' => 'Spezialist für Endoskopie und minimal-invasive Chirurgie',
                'is_active' => true,
                'sort_order' => 20,
            ],
            [
                'name' => 'B. Braun Melsungen AG',
                'website' => 'https://www.bbraun.com',
                'contact_person' => 'Support',
                'contact_email' => 'info@bbraun.com',
                'contact_phone' => '+49 5661 71-0',
                'description' => 'Medizintechnik und Pharmazeutika',
                'is_active' => true,
                'sort_order' => 30,
            ],
            [
                'name' => 'Johnson & Johnson Medical',
                'website' => 'https://www.jnjmedicaldevices.com',
                'contact_person' => 'Customer Service',
                'contact_email' => 'service@jnj.com',
                'contact_phone' => '+1 800-255-2500',
                'description' => 'Globaler Anbieter von Medizinprodukten',
                'is_active' => true,
                'sort_order' => 40,
            ],
        ];

        foreach ($manufacturers as $manufacturer) {
            Manufacturer::create($manufacturer);
        }
    }

    private function createDepartments(): void
    {
        $departments = [
            [
                'name' => 'Operationsbereich',
                'code' => 'OP',
                'location' => 'Ebene 3',
                'description' => 'Zentrale Operationsabteilung',
            ],
            [
                'name' => 'Sterilisationsabteilung',
                'code' => 'STERIL',
                'location' => 'Ebene 2',
                'description' => 'Zentrale Sterilgutversorgung',
            ],
            [
                'name' => 'Einkauf',
                'code' => 'PURCHASE',
                'location' => 'Verwaltung',
                'description' => 'Medizinischer Einkauf',
            ],
            [
                'name' => 'Kardiochirurgie',
                'code' => 'CARDIO',
                'location' => 'Ebene 4',
                'description' => 'Herzchirurgie',
            ],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }

    private function createOperatingRooms(): void
    {
        $opDept = Department::where('code', 'OP')->first();
        $cardioDept = Department::where('code', 'CARDIO')->first();

        $operatingRooms = [
            ['name' => 'OP-Saal 1', 'code' => 'OP1', 'location' => 'Ebene 3, Raum 301', 'department_id' => $opDept->id],
            ['name' => 'OP-Saal 2', 'code' => 'OP2', 'location' => 'Ebene 3, Raum 302', 'department_id' => $opDept->id],
            ['name' => 'OP-Saal 3', 'code' => 'OP3', 'location' => 'Ebene 3, Raum 303', 'department_id' => $opDept->id],
            ['name' => 'Kardiochirurgie OP', 'code' => 'COP1', 'location' => 'Ebene 4, Raum 401', 'department_id' => $cardioDept->id],
        ];

        foreach ($operatingRooms as $room) {
            OperatingRoom::create($room);
        }
    }

    private function createAdminUser(): void
    {
        $opDept = Department::where('code', 'OP')->first();

        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@hospital.de',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department_id' => $opDept->id,
            'is_active' => true,
        ]);
    }
}
