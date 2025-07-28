<?php

namespace App\Livewire\Instruments;

use App\Models\Instrument;
use App\Models\Container;
use App\Models\Department;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class EditInstrument extends Component
{
    public $instrumentId;
    public $instrument;
    public $isEditing = false;
    
    public $form = [
        'name' => '',
        'serial_number' => '',
        'manufacturer' => '',
        'model' => '',
        'category' => '',
        'purchase_price' => '',
        'purchase_date' => '',
        'warranty_until' => '',
        'description' => '',
        'status' => 'available',
        'current_container_id' => '',
        'current_location_id' => '',
    ];

    public function mount($instrument = null)
    {
        if ($instrument) {
            $this->instrumentId = $instrument;
            $this->instrument = Instrument::findOrFail($instrument);
            $this->isEditing = true;
            $this->loadInstrumentData();
            
            // Debug: Check what the dates look like
            Log::info('Loading instrument dates:', [
                'purchase_date_raw' => $this->instrument->purchase_date,
                'warranty_until_raw' => $this->instrument->warranty_until,
                'purchase_date_formatted' => $this->form['purchase_date'],
                'warranty_until_formatted' => $this->form['warranty_until'],
            ]);
        } else {
            $this->isEditing = false;
            $this->resetForm();
        }
    }

    public function loadInstrumentData()
    {
        $this->form = [
            'name' => $this->instrument->name,
            'serial_number' => $this->instrument->serial_number,
            'manufacturer' => $this->instrument->manufacturer,
            'model' => $this->instrument->model,
            'category' => $this->instrument->category,
            'purchase_price' => $this->instrument->purchase_price,
            'purchase_date' => $this->formatDateForInput($this->instrument->purchase_date),
            'warranty_until' => $this->formatDateForInput($this->instrument->warranty_until),
            'description' => $this->instrument->description,
            'status' => $this->instrument->status,
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
            'manufacturer' => '',
            'model' => '',
            'category' => '',
            'purchase_price' => '',
            'purchase_date' => '',
            'warranty_until' => '',
            'description' => '',
            'status' => 'available',
            'current_container_id' => '',
            'current_location_id' => '',
        ];
    }

    public function save()
    {
        $this->validate([
            'form.name' => 'required|string|max:255',
            'form.serial_number' => 'required|string|max:255|unique:instruments,serial_number,' . ($this->instrumentId ?? 'NULL'),
            'form.manufacturer' => 'nullable|string|max:255',
            'form.model' => 'nullable|string|max:255',
            'form.category' => 'required|in:scissors,forceps,scalpel,clamp,retractor,needle_holder',
            'form.purchase_price' => 'nullable|numeric|min:0',
            'form.purchase_date' => 'nullable|date',
            'form.warranty_until' => 'nullable|date',
            'form.description' => 'nullable|string',
            'form.status' => 'required|in:available,in_use,defective,in_repair,out_of_service',
            'form.current_container_id' => 'nullable|exists:containers,id',
            'form.current_location_id' => 'nullable|exists:departments,id',
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
            $this->instrument->update($data);
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
        $statuses = [
            'available' => 'Verfügbar',
            'in_use' => 'In Verwendung',
            'defective' => 'Defekt',
            'in_repair' => 'In Reparatur',
            'out_of_service' => 'Außer Betrieb'
        ];
        $categories = [
            'scissors' => 'Schere',
            'forceps' => 'Pinzette',
            'scalpel' => 'Skalpell',
            'clamp' => 'Klemme',
            'retractor' => 'Retraktor',
            'needle_holder' => 'Nadelhalter'
        ];

        return view('livewire.instruments.edit-instrument', compact(
            'containers',
            'departments', 
            'statuses', 
            'categories'
        ));
    }
}
