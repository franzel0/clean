<?php

namespace App\Livewire\Admin;

use App\Models\Instrument;
use Database\Seeders\SampleDataSeeder;
use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SampleDataManager extends Component
{
    public $hasSampleData = false;

    public function mount()
    {
        $this->checkForSampleData();
    }

    public function checkForSampleData()
    {
        // Check if we already have instruments (indicating sample data might exist)
        $this->hasSampleData = Instrument::count() > 0;
    }

    public function importSampleData()
    {
        try {
            // Run the sample data seeder
            Artisan::call('db:seed', ['--class' => SampleDataSeeder::class]);
            
            $this->checkForSampleData();
            
            session()->flash('message', 'Beispieldaten wurden erfolgreich importiert!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Fehler beim Importieren der Beispieldaten: ' . $e->getMessage());
        }
    }

    public function clearAllData()
    {
        try {
            // This is a more advanced feature - we'll just truncate sample tables for now
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            \App\Models\DefectReport::truncate();
            \App\Models\PurchaseOrder::truncate();
            \App\Models\Instrument::truncate();
            \App\Models\Container::truncate();
            
            // Keep admin user but remove sample users
            \App\Models\User::where('role', '!=', 'admin')->delete();
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->checkForSampleData();
            
            session()->flash('message', 'Alle Beispieldaten wurden erfolgreich gelöscht!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Fehler beim Löschen der Daten: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.sample-data-manager');
    }
}
