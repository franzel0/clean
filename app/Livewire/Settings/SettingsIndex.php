<?php

namespace App\Livewire\Settings;

use App\Models\OperatingRoom;
use App\Models\Department;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SettingsIndex extends Component
{
    use AuthorizesRequests;

    public $activeTab = 'instrument-categories';
    
    // Active item editing
    public $editingItem = null;
    public $editingValue = '';
    
    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    
    // Form data
    public $newValue = '';
    public $deleteItem = null;
    
    // Configuration arrays for different settings
    public $instrumentCategories = [
        'Scissors',
        'Forceps',
        'Clamps',
        'Retractors',
        'Probes',
        'Suction Devices',
        'Electrocautery',
        'Needle Holders',
        'Surgical Knives',
        'Other'
    ];
    
    public $instrumentStatuses = [
        'Available',
        'In Use',
        'Maintenance',
        'Out of Service',
        'Lost/Missing',
        'Retired'
    ];
    
    public $containerTypes = [
        'Sterilization Tray',
        'Storage Container',
        'Transport Case',
        'Specialty Tray',
        'Emergency Kit'
    ];
    
    public $containerStatuses = [
        'Available',
        'In Use',
        'Cleaning',
        'Sterilizing',
        'Maintenance',
        'Out of Service'
    ];
    
    public $defectTypes = [
        'Dull/Blunt',
        'Broken',
        'Bent',
        'Loose Joint',
        'Missing Part',
        'Corrosion',
        'Staining',
        'Electrical Issue',
        'Calibration Required',
        'Other'
    ];
    
    public $purchaseOrderStatuses = [
        'Requested',
        'Pending Approval',
        'Approved',
        'Ordered',
        'Shipped',
        'Received',
        'Cancelled',
        'Rejected'
    ];

    protected $rules = [
        'newValue' => 'required|string|max:255',
        'editingValue' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->authorize('viewAny', \App\Models\User::class);
    }

    public function render()
    {
        $operatingRooms = OperatingRoom::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        
        return view('livewire.settings.settings-index', [
            'operatingRooms' => $operatingRooms,
            'departments' => $departments
        ]);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetModal();
    }

    public function openCreateModal()
    {
        $this->resetModal();
        $this->showCreateModal = true;
    }

    public function openEditModal($index, $value)
    {
        $this->resetModal();
        $this->editingItem = $index;
        $this->editingValue = $value;
        $this->showEditModal = true;
    }

    public function openDeleteModal($index, $value)
    {
        $this->resetModal();
        $this->deleteItem = ['index' => $index, 'value' => $value];
        $this->showDeleteModal = true;
    }

    public function resetModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->editingItem = null;
        $this->editingValue = '';
        $this->newValue = '';
        $this->deleteItem = null;
        $this->resetValidation();
    }

    public function create()
    {
        $this->validate(['newValue' => 'required|string|max:255'], [
            'newValue.required' => 'Der Wert muss ausgefüllt werden.',
            'newValue.max' => 'Der Wert darf maximal 255 Zeichen lang sein.',
        ]);
        
        $property = $this->getActiveProperty();
        
        if (in_array($this->newValue, $this->$property)) {
            $this->addError('newValue', 'This value already exists.');
            return;
        }
        
        array_push($this->$property, $this->newValue);
        sort($this->$property);
        
        $this->resetModal();
        session()->flash('message', 'Item added successfully.');
    }

    public function update()
    {
        $this->validate(['editingValue' => 'required|string|max:255'], [
            'editingValue.required' => 'Der Wert muss ausgefüllt werden.',
            'editingValue.max' => 'Der Wert darf maximal 255 Zeichen lang sein.',
        ]);
        
        $property = $this->getActiveProperty();
        
        if (in_array($this->editingValue, $this->$property) && 
            $this->$property[$this->editingItem] !== $this->editingValue) {
            $this->addError('editingValue', 'This value already exists.');
            return;
        }
        
        $this->$property[$this->editingItem] = $this->editingValue;
        sort($this->$property);
        
        $this->resetModal();
        session()->flash('message', 'Item updated successfully.');
    }

    public function delete()
    {
        $property = $this->getActiveProperty();
        
        array_splice($this->$property, $this->deleteItem['index'], 1);
        
        $this->resetModal();
        session()->flash('message', 'Item deleted successfully.');
    }

    private function getActiveProperty()
    {
        return match ($this->activeTab) {
            'instrument-categories' => 'instrumentCategories',
            'instrument-statuses' => 'instrumentStatuses',
            'container-types' => 'containerTypes',
            'container-statuses' => 'containerStatuses',
            'defect-types' => 'defectTypes',
            'purchase-order-statuses' => 'purchaseOrderStatuses',
            default => 'instrumentCategories'
        };
    }

    public function getActiveData()
    {
        $property = $this->getActiveProperty();
        return $this->$property;
    }

    public function getActiveTitle()
    {
        return match ($this->activeTab) {
            'instrument-categories' => 'Instrument Categories',
            'instrument-statuses' => 'Instrument Statuses',
            'container-types' => 'Container Types',
            'container-statuses' => 'Container Statuses',
            'defect-types' => 'Defect Types',
            'purchase-order-statuses' => 'Purchase Order Statuses',
            'operating-rooms' => 'Operating Rooms',
            'departments' => 'Departments',
            default => 'Settings'
        };
    }
}
