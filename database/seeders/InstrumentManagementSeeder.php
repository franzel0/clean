<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\OperatingRoom;
use App\Models\Container;
use App\Models\Instrument;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InstrumentManagementSeeder extends Seeder
{
    public function run(): void
    {
        // Abteilungen erstellen
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

        $opDept = Department::where('code', 'OP')->first();
        $sterilDept = Department::where('code', 'STERIL')->first();
        $purchaseDept = Department::where('code', 'PURCHASE')->first();
        $cardioDept = Department::where('code', 'CARDIO')->first();

        // OP-Säle erstellen
        $operatingRooms = [
            ['name' => 'OP-Saal 1', 'code' => 'OP1', 'department_id' => $opDept->id],
            ['name' => 'OP-Saal 2', 'code' => 'OP2', 'department_id' => $opDept->id],
            ['name' => 'OP-Saal 3', 'code' => 'OP3', 'department_id' => $opDept->id],
            ['name' => 'Kardiochirurgie OP', 'code' => 'COP1', 'department_id' => $cardioDept->id],
        ];

        foreach ($operatingRooms as $room) {
            OperatingRoom::create($room);
        }

        // Container erstellen
        $containers = [
            ['name' => 'Basis-Set Allgemeinchirurgie', 'barcode' => 'BC001', 'type' => 'basic_set'],
            ['name' => 'Chirurgie-Set Laparoskopie', 'barcode' => 'BC002', 'type' => 'surgical_set'],
            ['name' => 'Spezial-Set Kardiochirurgie', 'barcode' => 'BC003', 'type' => 'special_set'],
            ['name' => 'Basis-Set Gefäßchirurgie', 'barcode' => 'BC004', 'type' => 'basic_set'],
        ];

        foreach ($containers as $container) {
            Container::create($container);
        }

        // Benutzer erstellen
        $users = [
            [
                'name' => 'Dr. Max Mustermann',
                'email' => 'admin@hospital.de',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'department_id' => $opDept->id,
            ],
            [
                'name' => 'Anna Steril',
                'email' => 'steril@hospital.de',
                'password' => Hash::make('password'),
                'role' => 'sterilization_staff',
                'department_id' => $sterilDept->id,
            ],
            [
                'name' => 'Dr. OP Personal',
                'email' => 'op@hospital.de',
                'password' => Hash::make('password'),
                'role' => 'or_staff',
                'department_id' => $opDept->id,
            ],
            [
                'name' => 'Einkauf Manager',
                'email' => 'purchase@hospital.de',
                'password' => Hash::make('password'),
                'role' => 'purchasing_staff',
                'department_id' => $purchaseDept->id,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Instrumente erstellen
        $containers = Container::all();
        $instruments = [
            [
                'name' => 'Chirurgische Schere Mayo',
                'serial_number' => 'INS-001',
                'manufacturer' => 'Aesculap',
                'model' => 'BC123',
                'category' => 'scissors',
                'purchase_price' => 89.50,
                'purchase_date' => '2024-01-15',
                'warranty_until' => '2026-01-15',
                'status' => 'available',
                'current_container_id' => $containers[0]->id,
                'current_location_id' => $sterilDept->id,
            ],
            [
                'name' => 'Anatomische Pinzette',
                'serial_number' => 'INS-002',
                'manufacturer' => 'Aesculap',
                'model' => 'BD456',
                'category' => 'forceps',
                'purchase_price' => 45.00,
                'purchase_date' => '2024-02-10',
                'warranty_until' => '2026-02-10',
                'status' => 'defective',
                'current_container_id' => $containers[0]->id,
                'current_location_id' => $sterilDept->id,
            ],
            [
                'name' => 'Nadelhalter',
                'serial_number' => 'INS-003',
                'manufacturer' => 'Karl Storz',
                'model' => 'NH789',
                'category' => 'needle_holder',
                'purchase_price' => 125.00,
                'purchase_date' => '2024-03-05',
                'warranty_until' => '2026-03-05',
                'status' => 'in_use',
                'current_container_id' => $containers[1]->id,
                'current_location_id' => $opDept->id,
            ],
        ];

        foreach ($instruments as $instrument) {
            Instrument::create($instrument);
        }
    }
}
