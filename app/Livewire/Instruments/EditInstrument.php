<?php

namespace App\Livewire\Instruments;

use App\Models\Instrument;
use App\Models\Container;
use App\Models\Department;
use App\Models\InstrumentCategory;
use App\Models\InstrumentStatus;
use App\Models\Manufacturer;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class EditInstrument extends Component
{
    public $instrumentId;
    public $instrument;
    public $isEditing = false;
    
    // Lookup-Daten
    public $manufacturers;
    public $categories;
    public $statuses;
    public $containers;
    public $departments;
    
    public $form = [
        'name' => '',
        'serial_number' => '',
        'manufacturer_id' => '',
        'model' => '',
        'category_id' => '',
        'purchase_price' => '',
        'purchase_date' => '',
        'warranty_until' => '',
        'description' => '',
        'status_id' => '',
        'current_container_id' => '',
        'current_location_id' => '',
    ];

    public function mount($instrument = null)
    {
        try {
            if ($instrument) {
                $this->instrumentId = $instrument;
                $this->instrument = Instrument::with(['category', 'manufacturerRelation', 'instrumentStatus'])
                    ->findOrFail($instrument);
                
                // Sichere Zuweisung der Form-Daten mit Nullsafe Operator
                $this->form = [
                    'name' => $this->instrument->name ?? '',
                    'serial_number' => $this->instrument->serial_number ?? '',
                    'manufacturer_id' => $this->instrument->manufacturer_id ?? '',
                    'model' => $this->instrument->model ?? '',
                    'category_id' => $this->instrument->category_id ?? '',
                    'purchase_price' => $this->instrument->purchase_price ? (string) $this->instrument->purchase_price : '',
                    'purchase_date' => $this->instrument->purchase_date?->format('Y-m-d') ?? '',
                    'warranty_until' => $this->instrument->warranty_until?->format('Y-m-d') ?? '',
                    'description' => $this->instrument->description ?? '',
                    'status_id' => $this->instrument->status_id ?? '',
                    'current_container_id' => $this->instrument->current_container_id ?? '',
                    'current_location_id' => $this->instrument->current_location_id ?? '',
                ];
            }
            
            // Lade Lookup-Daten sicher
            $this->loadLookupData();
        } catch (\Exception $e) {
            Log::error('Error in EditInstrument mount: ' . $e->getMessage());
            session()->flash('error', 'Fehler beim Laden des Instruments: ' . $e->getMessage());
            return redirect()->route('instruments.index');
        }
    }
    
    private function loadLookupData()
    {
        try {
            $this->manufacturers = Manufacturer::orderBy('name')->get();
            $this->categories = InstrumentCategory::orderBy('name')->get();
            $this->statuses = InstrumentStatus::orderBy('name')->get();
            $this->containers = Container::where('is_active', true)->orderBy('name')->get();
            $this->departments = Department::orderBy('name')->get();
        } catch (\Exception $e) {
            Log::error('Error loading lookup data: ' . $e->getMessage());
            // Fallback mit leeren Collections
            $this->manufacturers = collect([]);
            $this->categories = collect([]);
            $this->statuses = collect([]);
            $this->containers = collect([]);
            $this->departments = collect([]);
        }
    }

    public function loadInstrumentData()
    {
        $this->form = [
            'name' => $this->instrument->name,
            'serial_number' => $this->instrument->serial_number,
            'manufacturer_id' => $this->instrument->manufacturer_id,
            'model' => $this->instrument->model,
            'category_id' => $this->instrument->category_id,
            'purchase_price' => $this->instrument->purchase_price,
            'purchase_date' => $this->formatDateForInput($this->instrument->purchase_date),
            'warranty_until' => $this->formatDateForInput($this->instrument->warranty_until),
            'description' => $this->instrument->description,
            'status_id' => $this->instrument->status_id,
            'current_container_id' => $this->instrument->current_container_id,
            'current_location_id' => $this->instrument->current_location_id,
        ];
    }

    private function formatDateForInput($date)
    {
        if (!$date) {
            return '';
        }
        
        // If it's already a string in the right format
        if (is_string($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }
        
        // If it's a Carbon instance
        if ($date instanceof \Carbon\Carbon) {
            return $date->format('Y-m-d');
        }
        
        // Try to parse it
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return '';
        }
    }

    public function resetForm()
    {
        $this->form = [
            'name' => '',
            'serial_number' => '',
            'manufacturer_id' => '',
            'model' => '',
            'category_id' => '',
            'purchase_price' => '',
            'purchase_date' => '',
            'warranty_until' => '',
            'description' => '',
            'status_id' => '',
            'current_container_id' => '',
            'current_location_id' => '',
        ];
    }

    public function save()
    {
        $this->validate([
            'form.name' => 'required|string|max:255',
            'form.serial_number' => 'required|string|max:255|unique:instruments,serial_number,' . ($this->instrumentId ?? 'NULL'),
            'form.manufacturer_id' => 'nullable|exists:manufacturers,id',
            'form.model' => 'nullable|string|max:255',
            'form.category_id' => 'required|exists:instrument_categories,id',
            'form.purchase_price' => 'nullable|numeric|min:0',
            'form.purchase_date' => 'nullable|date',
            'form.warranty_until' => 'nullable|date',
            'form.description' => 'nullable|string',
            'form.status_id' => 'required|exists:instrument_statuses,id',
            'form.current_container_id' => 'nullable|exists:containers,id',
            'form.current_location_id' => 'nullable|exists:departments,id',
        ], [
            'form.name.required' => 'Der Name des Instruments muss ausgefüllt werden.',
            'form.name.max' => 'Der Name darf maximal 255 Zeichen lang sein.',
            'form.serial_number.required' => 'Die Seriennummer muss ausgefüllt werden.',
            'form.serial_number.unique' => 'Diese Seriennummer ist bereits vergeben.',
            'form.serial_number.max' => 'Die Seriennummer darf maximal 255 Zeichen lang sein.',
            'form.manufacturer_id.exists' => 'Bitte wählen Sie einen gültigen Hersteller aus.',
            'form.model.max' => 'Das Modell darf maximal 255 Zeichen lang sein.',
            'form.category_id.required' => 'Bitte wählen Sie eine Kategorie aus.',
            'form.category_id.exists' => 'Bitte wählen Sie eine gültige Kategorie aus.',
            'form.purchase_price.numeric' => 'Der Kaufpreis muss eine Zahl sein.',
            'form.purchase_price.min' => 'Der Kaufpreis kann nicht negativ sein.',
            'form.purchase_date.date' => 'Bitte geben Sie ein gültiges Kaufdatum ein.',
            'form.warranty_until.date' => 'Bitte geben Sie ein gültiges Garantie-Ende-Datum ein.',
            'form.status_id.required' => 'Bitte wählen Sie einen Status aus.',
            'form.status_id.exists' => 'Bitte wählen Sie einen gültigen Status aus.',
            'form.current_container_id.exists' => 'Bitte wählen Sie einen gültigen Container aus.',
            'form.current_location_id.exists' => 'Bitte wählen Sie einen gültigen Standort aus.',
        ]);

        $data = $this->form;
        
        // Clean up empty values but preserve date formatting
        foreach ($data as $key => $value) {
            if ($value === '' && !in_array($key, ['purchase_date', 'warranty_until'])) {
                $data[$key] = null;
            }
        }
        
        // Handle date fields specifically
        if (empty($data['purchase_date'])) {
            $data['purchase_date'] = null;
        }
        if (empty($data['warranty_until'])) {
            $data['warranty_until'] = null;
        }

        if ($this->isEditing) {
            // Speichere den alten Status für Movement-Logging
            $oldStatusId = $this->instrument->status_id;
            $newStatusId = $data['status_id'];
            
            $this->instrument->update($data);
            
            // Erstelle Movement wenn Status geändert wurde
            if ($oldStatusId != $newStatusId) {
                \App\Services\MovementService::logStatusChange(
                    $this->instrument,
                    $newStatusId,
                    'Status geändert über Instrumentenbearbeitung'
                );
            }
            
            session()->flash('message', 'Instrument erfolgreich aktualisiert.');
        } else {
            Instrument::create($data);
            session()->flash('message', 'Instrument erfolgreich erstellt.');
        }

        return redirect()->route('instruments.index');
    }

    public function cancel()
    {
        return redirect()->route('instruments.index');
    }

    public function render()
    {
        $containers = Container::all();
        $departments = Department::all();
        $statuses = InstrumentStatus::active()->ordered()->get();
        $categories = InstrumentCategory::active()->ordered()->get();
        $manufacturers = Manufacturer::active()->ordered()->get();

        return view('livewire.instruments.edit-instrument', compact(
            'containers',
            'departments', 
            'statuses', 
            'categories',
            'manufacturers'
        ));
    }
}
