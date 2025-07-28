<?php

namespace Database\Seeders;

use App\Models\InstrumentCategory;
use App\Models\InstrumentStatus;
use App\Models\ContainerType;
use App\Models\ContainerStatus;
use App\Models\DefectType;
use App\Models\PurchaseOrderStatus;
use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Instrument Kategorien
        $instrumentCategories = [
            ['name' => 'Scheren', 'description' => 'Chirurgische Scheren aller Art', 'sort_order' => 1],
            ['name' => 'Pinzetten', 'description' => 'Anatomische und chirurgische Pinzetten', 'sort_order' => 2],
            ['name' => 'Klemmen', 'description' => 'Arterien- und Gewebsklemmen', 'sort_order' => 3],
            ['name' => 'Retraktor', 'description' => 'Wundhaken und Spreizer', 'sort_order' => 4],
            ['name' => 'Sonden', 'description' => 'Untersuchungssonden', 'sort_order' => 5],
            ['name' => 'Sauggeräte', 'description' => 'Absauggeräte und Kanülen', 'sort_order' => 6],
            ['name' => 'Elektrokauter', 'description' => 'Elektrochirurgische Instrumente', 'sort_order' => 7],
            ['name' => 'Nadelhalter', 'description' => 'Nadelhalter verschiedener Größen', 'sort_order' => 8],
            ['name' => 'Skalpelle', 'description' => 'Chirurgische Messer und Klingen', 'sort_order' => 9],
            ['name' => 'Sonstiges', 'description' => 'Andere chirurgische Instrumente', 'sort_order' => 10],
        ];

        foreach ($instrumentCategories as $category) {
            InstrumentCategory::create($category);
        }

        // Instrument Status
        $instrumentStatuses = [
            ['name' => 'Verfügbar', 'description' => 'Instrument ist einsatzbereit', 'color' => 'green', 'sort_order' => 1],
            ['name' => 'In Benutzung', 'description' => 'Instrument wird gerade verwendet', 'color' => 'blue', 'sort_order' => 2],
            ['name' => 'Wartung', 'description' => 'Instrument wird gewartet', 'color' => 'yellow', 'sort_order' => 3],
            ['name' => 'Außer Betrieb', 'description' => 'Instrument ist defekt', 'color' => 'red', 'sort_order' => 4],
            ['name' => 'Verloren/Vermisst', 'description' => 'Instrument ist verloren', 'color' => 'gray', 'sort_order' => 5],
            ['name' => 'Aussortiert', 'description' => 'Instrument ist nicht mehr verwendbar', 'color' => 'black', 'sort_order' => 6],
        ];

        foreach ($instrumentStatuses as $status) {
            InstrumentStatus::create($status);
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

        // Bestellstatus
        $purchaseOrderStatuses = [
            ['name' => 'Angefordert', 'description' => 'Bestellung wurde angefordert', 'color' => 'gray', 'sort_order' => 1],
            ['name' => 'Prüfung Pending', 'description' => 'Bestellung wartet auf Genehmigung', 'color' => 'yellow', 'sort_order' => 2],
            ['name' => 'Genehmigt', 'description' => 'Bestellung wurde genehmigt', 'color' => 'green', 'sort_order' => 3],
            ['name' => 'Bestellt', 'description' => 'Bestellung wurde aufgegeben', 'color' => 'blue', 'sort_order' => 4],
            ['name' => 'Versandt', 'description' => 'Bestellung wurde versandt', 'color' => 'purple', 'sort_order' => 5],
            ['name' => 'Erhalten', 'description' => 'Bestellung wurde erhalten', 'color' => 'green', 'sort_order' => 6],
            ['name' => 'Storniert', 'description' => 'Bestellung wurde storniert', 'color' => 'red', 'sort_order' => 7],
            ['name' => 'Abgelehnt', 'description' => 'Bestellung wurde abgelehnt', 'color' => 'red', 'sort_order' => 8],
        ];

        foreach ($purchaseOrderStatuses as $status) {
            PurchaseOrderStatus::create($status);
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
