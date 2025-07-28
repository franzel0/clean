<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hersteller
        $manufacturers = [
            ['name' => 'Aesculap', 'website' => 'https://www.aesculap.com', 'contact_email' => 'info@aesculap.com', 'description' => 'Führender Hersteller chirurgischer Instrumente', 'sort_order' => 1],
            ['name' => 'Karl Storz', 'website' => 'https://www.karlstorz.com', 'contact_email' => 'info@karlstorz.com', 'description' => 'Spezialist für Endoskopie und minimal-invasive Chirurgie', 'sort_order' => 2],
            ['name' => 'Olympus', 'website' => 'https://www.olympus.com', 'contact_email' => 'medical@olympus.com', 'description' => 'Medizintechnik und Endoskopie', 'sort_order' => 3],
            ['name' => 'Medtronic', 'website' => 'https://www.medtronic.com', 'contact_email' => 'info@medtronic.com', 'description' => 'Medizintechnik und Implantate', 'sort_order' => 4],
            ['name' => 'Johnson & Johnson', 'website' => 'https://www.jnj.com', 'contact_email' => 'medical@jnj.com', 'description' => 'Medizinprodukte und Pharma', 'sort_order' => 5],
            ['name' => 'Stryker', 'website' => 'https://www.stryker.com', 'contact_email' => 'info@stryker.com', 'description' => 'Orthopädie und Chirurgie', 'sort_order' => 6],
            ['name' => 'Zimmer Biomet', 'website' => 'https://www.zimmerbiomet.com', 'contact_email' => 'info@zimmerbiomet.com', 'description' => 'Orthopädische Implantate', 'sort_order' => 7],
            ['name' => 'Braun', 'website' => 'https://www.bbraun.com', 'contact_email' => 'info@bbraun.com', 'description' => 'Medizintechnik und Pharma', 'sort_order' => 8],
            ['name' => 'Erbe', 'website' => 'https://www.erbe-med.com', 'contact_email' => 'info@erbe-med.com', 'description' => 'Elektrochirurgie', 'sort_order' => 9],
            ['name' => 'Sonstiger Hersteller', 'website' => null, 'contact_email' => null, 'description' => 'Andere Hersteller', 'sort_order' => 10],
        ];

        foreach ($manufacturers as $manufacturer) {
            Manufacturer::create($manufacturer);
        }
    }
}
