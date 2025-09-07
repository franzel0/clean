<?php

namespace Database\Seeders;

use App\Models\InstrumentStatus;
use App\Models\ContainerType;
use App\Models\ContainerStatus;
use App\Models\DefectType;
use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Nur Instrument Status erstellen - Categories werden vom BaseConfigurationSeeder erstellt
        
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

        // Container Arten
        $containerTypes = [
            ['name' => 'Sterilisations-Schale', 'description' => 'Standard Sterilisationsschale', 'sort_order' => 1],
            ['name' => 'Lagercontainer', 'description' => 'Container für die Lagerung', 'sort_order' => 2],
            ['name' => 'Transport-Koffer', 'description' => 'Mobiler Transportkoffer', 'sort_order' => 3],
            ['name' => 'Spezial-Schale', 'description' => 'Speziell angepasste Instrumentenschale', 'sort_order' => 4],
            ['name' => 'Notfall-Set', 'description' => 'Container für Notfallinstrumente', 'sort_order' => 5],
        ];

        foreach ($containerTypes as $type) {
            ContainerType::create($type);
        }

        // Container Status
        $containerStatuses = [
            ['name' => 'Verfügbar', 'description' => 'Container ist bereit', 'color' => 'green', 'sort_order' => 1],
            ['name' => 'In Benutzung', 'description' => 'Container wird verwendet', 'color' => 'blue', 'sort_order' => 2],
            ['name' => 'Reinigung', 'description' => 'Container wird gereinigt', 'color' => 'yellow', 'sort_order' => 3],
            ['name' => 'Sterilisation', 'description' => 'Container wird sterilisiert', 'color' => 'orange', 'sort_order' => 4],
            ['name' => 'Wartung', 'description' => 'Container wird gewartet', 'color' => 'purple', 'sort_order' => 5],
            ['name' => 'Außer Betrieb', 'description' => 'Container ist defekt', 'color' => 'red', 'sort_order' => 6],
        ];

        foreach ($containerStatuses as $status) {
            ContainerStatus::create($status);
        }

        // Defekt Arten
        $defectTypes = [
            ['name' => 'Stumpf/Abgenutzt', 'description' => 'Schneidwerkzeug ist stumpf', 'severity' => 'medium', 'sort_order' => 1],
            ['name' => 'Gebrochen', 'description' => 'Instrument ist gebrochen', 'severity' => 'high', 'sort_order' => 2],
            ['name' => 'Verbogen', 'description' => 'Instrument ist verbogen', 'severity' => 'medium', 'sort_order' => 3],
            ['name' => 'Lockeres Gelenk', 'description' => 'Gelenk ist locker', 'severity' => 'medium', 'sort_order' => 4],
            ['name' => 'Fehlendes Teil', 'description' => 'Ein Teil fehlt', 'severity' => 'high', 'sort_order' => 5],
            ['name' => 'Korrosion', 'description' => 'Instrument ist korrodiert', 'severity' => 'high', 'sort_order' => 6],
            ['name' => 'Verfärbung', 'description' => 'Verfärbung des Instruments', 'severity' => 'low', 'sort_order' => 7],
            ['name' => 'Elektrisches Problem', 'description' => 'Problem mit Elektronik', 'severity' => 'critical', 'sort_order' => 8],
            ['name' => 'Kalibrierung erforderlich', 'description' => 'Instrument muss kalibriert werden', 'severity' => 'medium', 'sort_order' => 9],
            ['name' => 'Sonstiges', 'description' => 'Anderer Defekt', 'severity' => 'medium', 'sort_order' => 10],
        ];

        foreach ($defectTypes as $type) {
            DefectType::create($type);
        }

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
