<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Erst einige bekannte deutsche Medizintechnik-Unternehmen erstellen
        $knownSuppliers = [
            [
                'name' => 'Aesculap AG',
                'contact_person' => 'Max Mustermann',
                'email' => 'order@aesculap.de',
                'phone' => '+49 7461 95-0',
                'address' => 'Am Aesculap-Platz, 78532 Tuttlingen',
                'website' => 'https://www.aesculap.de',
                'notes' => 'Führender Hersteller von chirurgischen Instrumenten und Implantaten',
                'is_active' => true,
            ],
            [
                'name' => 'Karl Storz SE & Co. KG',
                'contact_person' => 'Anna Schmidt',
                'email' => 'info@karlstorz.com',
                'phone' => '+49 7461 708-0',
                'address' => 'Dr.-Karl-Storz-Straße 34, 78532 Tuttlingen',
                'website' => 'https://www.karlstorz.com',
                'notes' => 'Spezialist für Endoskope und minimal-invasive Chirurgie',
                'is_active' => true,
            ],
            [
                'name' => 'Olympus Europa SE & Co. KG',
                'contact_person' => 'Peter Weber',
                'email' => 'medical@olympus.de',
                'phone' => '+49 40 23773-0',
                'address' => 'Wendenstraße 14-18, 20097 Hamburg',
                'website' => 'https://www.olympus.de',
                'notes' => 'Endoskopie und bildgebende Verfahren',
                'is_active' => true,
            ],
            [
                'name' => 'B. Braun Melsungen AG',
                'contact_person' => 'Lisa Müller',
                'email' => 'info@bbraun.com',
                'phone' => '+49 5661 71-0',
                'address' => 'Carl-Braun-Straße 1, 34212 Melsungen',
                'website' => 'https://www.bbraun.de',
                'notes' => 'Medizintechnik und Pharmazeutika',
                'is_active' => true,
            ],
            [
                'name' => 'Johnson & Johnson Medical GmbH',
                'contact_person' => 'Thomas Bauer',
                'email' => 'info@its.jnj.com',
                'phone' => '+49 2151 81-0',
                'address' => 'Robert-Koch-Straße 1, 41453 Neuss',
                'website' => 'https://www.jnjmedical.de',
                'notes' => 'Chirurgische Instrumente und Implantate',
                'is_active' => true,
            ],
        ];

        foreach ($knownSuppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Dann noch weitere zufällige Lieferanten mit der Factory erstellen
        Supplier::factory(10)->create();
    }
}
