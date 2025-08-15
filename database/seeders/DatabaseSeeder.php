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
        ]);

        // Ask if sample data should be included
        if ($this->command->confirm('Möchten Sie Beispieldaten einfügen? (Instrumente, Container, Defektberichte, Bestellungen)', false)) {
            $this->call([
                SampleDataSeeder::class,
            ]);
        }
    }
}
