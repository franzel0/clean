<?php

namespace App\Livewire\Instruments;

use App\Models\Instrument;
use App\Models\Container;
use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Neues Instrument')]
class CreateInstrument extends Component
{
    public $name = '';
    public $serial_number = '';
    public $manufacturer = '';
    public $model = '';
    public $category = '';
    public $purchase_price = '';
    public $purchase_date = '';
    public $warranty_until = '';
    public $description = '';
    public $status = 'available';
    public $current_container_id = '';
    public $current_location_id = '';

    public function mount()
    {
        // Check if container parameter is passed via URL
        if (request()->has('container')) {
            $this->current_container_id = request()->get('container');
        }
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'serial_number' => 'required|string|max:255|unique:instruments',
        'manufacturer' => 'nullable|string|max:255',
        'model' => 'nullable|string|max:255',
        'category' => 'required|string',
        'purchase_price' => 'nullable|numeric|min:0',
        'purchase_date' => 'nullable|date',
        'warranty_until' => 'nullable|date|after:purchase_date',
        'description' => 'nullable|string',
        'status' => 'required|string',
        'current_container_id' => 'nullable|exists:containers,id',
        'current_location_id' => 'nullable|exists:departments,id',
    ];

    public function save()
    {
        $this->validate();

        $instrument = Instrument::create([
            'name' => $this->name,
            'serial_number' => $this->serial_number,
            'manufacturer' => $this->manufacturer,
            'model' => $this->model,
            'category' => $this->category,
            'purchase_price' => $this->purchase_price ?: null,
            'purchase_date' => $this->purchase_date ?: null,
            'warranty_until' => $this->warranty_until ?: null,
            'description' => $this->description,
            'status' => $this->status,
            'current_container_id' => $this->current_container_id ?: null,
            'current_location_id' => $this->current_location_id ?: null,
            'is_active' => true,
        ]);

        session()->flash('message', 'Instrument erfolgreich erstellt!');
        
        return redirect()->route('instruments.show', $instrument);
    }

    public function saveAndCreateDefectReport()
    {
        $this->validate();

        $instrument = Instrument::create([
            'name' => $this->name,
            'serial_number' => $this->serial_number,
            'manufacturer' => $this->manufacturer,
            'model' => $this->model,
            'category' => $this->category,
            'purchase_price' => $this->purchase_price ?: null,
            'purchase_date' => $this->purchase_date ?: null,
            'warranty_until' => $this->warranty_until ?: null,
            'description' => $this->description,
            'status' => $this->status,
            'current_container_id' => $this->current_container_id ?: null,
            'current_location_id' => $this->current_location_id ?: null,
            'is_active' => true,
        ]);

        session()->flash('message', 'Instrument erfolgreich erstellt! Erstelle jetzt eine Defektmeldung...');
        
        return redirect()->route('defect-reports.create', ['instrument' => $instrument->id]);
    }

    public function render()
    {
        $containers = Container::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        
        $categories = [
            'scissors' => 'Scheren',
            'forceps' => 'Pinzetten',
            'scalpel' => 'Skalpelle',
            'clamp' => 'Klemmen',
            'retractor' => 'Wundhaken',
            'needle_holder' => 'Nadelhalter',
        ];

        $statuses = [
            'available' => 'Verfügbar',
            'in_use' => 'Im Einsatz',
            'defective' => 'Defekt',
            'in_repair' => 'In Reparatur',
            'out_of_service' => 'Außer Betrieb',
        ];

        return view('livewire.instruments.create-instrument', [
            'containers' => $containers,
            'departments' => $departments,
            'categories' => $categories,
            'statuses' => $statuses,
        ]);
    }
}
