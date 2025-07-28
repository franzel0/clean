<?php

namespace App\Livewire\Containers;

use App\Models\Container;
use App\Models\ContainerType;
use App\Models\ContainerStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Neuen Container erstellen')]
class CreateContainer extends Component
{
    public $name = '';
    public $barcode = '';
    public $type = '';
    public $type_id = '';
    public $status = '';
    public $status_id = '';
    public $description = '';
    public $is_active = true;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'barcode' => 'required|string|min:3|max:255|unique:containers,barcode',
            'type' => 'nullable|string|max:255',
            'type_id' => 'required|exists:container_types,id',
            'status' => 'nullable|string|max:255',
            'status_id' => 'required|exists:container_statuses,id',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Der Name des Containers muss ausgefüllt werden.',
            'name.min' => 'Der Name muss mindestens 3 Zeichen lang sein.',
            'name.max' => 'Der Name darf maximal 255 Zeichen lang sein.',
            'barcode.required' => 'Der Barcode muss ausgefüllt werden.',
            'barcode.min' => 'Der Barcode muss mindestens 3 Zeichen lang sein.',
            'barcode.max' => 'Der Barcode darf maximal 255 Zeichen lang sein.',
            'barcode.unique' => 'Dieser Barcode ist bereits vergeben.',
            'type_id.required' => 'Bitte wählen Sie einen Container-Typ aus.',
            'type_id.exists' => 'Bitte wählen Sie einen gültigen Container-Typ aus.',
            'status_id.required' => 'Bitte wählen Sie einen Status aus.',
            'status_id.exists' => 'Bitte wählen Sie einen gültigen Status aus.',
            'description.max' => 'Die Beschreibung darf maximal 1000 Zeichen lang sein.',
        ]);

        $container = Container::create([
            'name' => $this->name,
            'barcode' => $this->barcode,
            'type' => $this->type,
            'type_id' => $this->type_id,
            'status' => $this->status,
            'status_id' => $this->status_id,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Container wurde erfolgreich erstellt.');
        
        return redirect()->route('containers.show', $container);
    }

    public function render()
    {
        $containerTypes = ContainerType::active()->ordered()->get();
        $containerStatuses = ContainerStatus::active()->ordered()->get();
        
        return view('livewire.containers.create-container', compact('containerTypes', 'containerStatuses'));
    }
}
