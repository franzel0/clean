<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Always run base configuration
        $this->call([
            BaseConfigurationSeeder::class,
            InstrumentStatusSeeder::class,  // Erstellt InstrumentStatuses
            InstrumentStatusContextSeeder::class,  // Setzt Context-Availability
            SampleDataSeeder::class,  // Erstellt Beispieldaten
        ]);

    }
}
