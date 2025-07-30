<?php

namespace App\Livewire\AppSettings;

use App\Models\OperatingRoom;
use App\Models\Department;
use App\Models\InstrumentCategory;
use App\Models\InstrumentStatus;
use App\Models\ContainerType;
use App\Models\ContainerStatus;
use App\Models\DefectType;
use App\Models\PurchaseOrderStatus;
use App\Models\Manufacturer;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SettingsIndex extends Component
{
    use AuthorizesRequests;

    public $activeTab = 'instrument-categories';
    
    // Active item editing
    public $editingItem = null;
    public $editingValue = '';
    public $editingDescription = '';
    public $editingColor = '';
    public $editingSeverity = '';
    public $editingSortOrder = 0;
    public $editingCode = '';
    public $editingLocation = '';
    public $editingDepartmentId = null;
    
    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    
    // Form data
    public $newValue = '';
    public $newDescription = '';
    public $newColor = 'gray';
    public $newSeverity = 'medium';
    public $newSortOrder = 0;
    public $newCode = '';
    public $newLocation = '';
    public $newDepartmentId = null;
    
    // Manufacturer contact fields
    public $newContactPerson = '';
    public $newContactPhone = '';
    public $newContactEmail = '';
    public $newWebsite = '';
    public $editingContactPerson = '';
    public $editingContactPhone = '';
    public $editingContactEmail = '';
    public $editingWebsite = '';
    
    public $deleteItem = null;
    
    protected $rules = [
        'newValue' => 'required|string|max:255',
        'editingValue' => 'required|string|max:255',
        'newDescription' => 'nullable|string|max:1000',
        'editingDescription' => 'nullable|string|max:1000',
        'newColor' => 'nullable|string|max:50',
        'editingColor' => 'nullable|string|max:50',
        'newSeverity' => 'nullable|string|max:50',
        'editingSeverity' => 'nullable|string|max:50',
        'newSortOrder' => 'nullable|integer|min:0',
        'editingSortOrder' => 'nullable|integer|min:0',
        'newCode' => 'nullable|string|max:50',
        'editingCode' => 'nullable|string|max:50',
        'newLocation' => 'nullable|string|max:255',
        'editingLocation' => 'nullable|string|max:255',
        'newDepartmentId' => 'nullable|integer|exists:departments,id',
        'editingDepartmentId' => 'nullable|integer|exists:departments,id',
        'newContactPerson' => 'nullable|string|max:255',
        'editingContactPerson' => 'nullable|string|max:255',
        'newContactEmail' => 'nullable|email|max:255',
        'editingContactEmail' => 'nullable|email|max:255',
        'newContactPhone' => 'nullable|string|max:50',
        'editingContactPhone' => 'nullable|string|max:50',
        'newWebsite' => 'nullable|url|max:255',
        'editingWebsite' => 'nullable|url|max:255',
    ];

    public function mount()
    {
        $this->authorize('viewAny', \App\Models\User::class);
    }

    public function render()
    {
        $operatingRooms = OperatingRoom::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        
        $data = $this->getActiveData();
        
        return view('livewire.app-settings.settings-index', [
            'operatingRooms' => $operatingRooms,
            'departments' => $departments,
            'data' => $data
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

    public function openEditModal($id)
    {
        $this->resetModal();
        $model = $this->getActiveModel();
        $item = $model::find($id);
        
        if ($item) {
            $this->editingItem = $id;
            $this->editingValue = $item->name;
            $this->editingDescription = $item->description ?? '';
            $this->editingColor = $item->color ?? 'gray';
            $this->editingSeverity = $item->severity ?? 'medium';
            
            // Only set sort_order for models that have it
            if (!in_array($this->activeTab, ['operating-rooms', 'departments'])) {
                $this->editingSortOrder = $item->sort_order ?? 0;
            }
            
            // Load model-specific fields
            if (in_array($this->activeTab, ['operating-rooms', 'departments'])) {
                $this->editingCode = $item->code ?? '';
                $this->editingLocation = $item->location ?? '';
            }

            if ($this->activeTab === 'operating-rooms') {
                $this->editingDepartmentId = $item->department_id;
            }

            if ($this->activeTab === 'manufacturers') {
                $this->editingContactPerson = $item->contact_person ?? '';
                $this->editingContactEmail = $item->contact_email ?? '';
                $this->editingContactPhone = $item->contact_phone ?? '';
                $this->editingWebsite = $item->website ?? '';
            }
            
            $this->showEditModal = true;
        }
    }

    public function openDeleteModal($id)
    {
        $this->resetModal();
        $model = $this->getActiveModel();
        $item = $model::find($id);
        
        if ($item) {
            $this->deleteItem = ['id' => $id, 'value' => $item->name];
            $this->showDeleteModal = true;
        }
    }

    public function resetModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->editingItem = null;
        $this->editingValue = '';
        $this->editingDescription = '';
        $this->editingColor = 'gray';
        $this->editingSeverity = 'medium';
        $this->editingSortOrder = 0;
        $this->editingCode = '';
        $this->editingLocation = '';
        $this->editingDepartmentId = null;
        $this->newValue = '';
        $this->newDescription = '';
        $this->newColor = 'gray';
        $this->newSeverity = 'medium';
        $this->newSortOrder = 0;
        $this->newCode = '';
        $this->newLocation = '';
        $this->newDepartmentId = null;
        $this->newContactPerson = '';
        $this->newContactEmail = '';
        $this->newContactPhone = '';
        $this->newWebsite = '';
        $this->editingContactPerson = '';
        $this->editingContactEmail = '';
        $this->editingContactPhone = '';
        $this->editingWebsite = '';
        $this->deleteItem = null;
        $this->resetValidation();
    }

    public function create()
    {
        $validationRules = ['newValue' => 'required|string|max:255'];
        $validationMessages = [
            'newValue.required' => 'Der Wert muss ausgefüllt werden.',
            'newValue.max' => 'Der Wert darf maximal 255 Zeichen lang sein.',
        ];
        
        // Add validation for model-specific fields
        if (in_array($this->activeTab, ['operating-rooms', 'departments'])) {
            $validationRules['newCode'] = 'required|string|max:50';
            $validationRules['newLocation'] = 'required|string|max:255';
            $validationMessages['newCode.required'] = 'Der Code muss ausgefüllt werden.';
            $validationMessages['newLocation.required'] = 'Der Standort muss ausgefüllt werden.';
        }
        
        if ($this->activeTab === 'operating-rooms') {
            $validationRules['newDepartmentId'] = 'required|integer|exists:departments,id';
            $validationMessages['newDepartmentId.required'] = 'Die Abteilung muss ausgewählt werden.';
        }
        
        $this->validate($validationRules, $validationMessages);
        
        $model = $this->getActiveModel();
        
        // Check for duplicate names
        if ($model::where('name', $this->newValue)->exists()) {
            $this->addError('newValue', 'Dieser Wert existiert bereits.');
            return;
        }
        
        $data = [
            'name' => $this->newValue,
            'description' => $this->newDescription,
            'is_active' => true,
        ];

        // Add sort_order only for models that support it
        if (!in_array($this->activeTab, ['operating-rooms', 'departments'])) {
            $data['sort_order'] = $this->newSortOrder ?: ($model::max('sort_order') ?? 0) + 1;
        }

        // Add model-specific fields
        if (in_array($this->activeTab, ['instrument-statuses', 'container-statuses', 'purchase-order-statuses'])) {
            $data['color'] = $this->newColor;
        }

        if ($this->activeTab === 'defect-types') {
            $data['severity'] = $this->newSeverity;
        }

        if (in_array($this->activeTab, ['operating-rooms', 'departments'])) {
            $data['code'] = $this->newCode;
            $data['location'] = $this->newLocation;
        }

        if ($this->activeTab === 'operating-rooms') {
            $data['department_id'] = $this->newDepartmentId;
        }

        if ($this->activeTab === 'manufacturers') {
            $data['contact_person'] = $this->newContactPerson;
            $data['contact_email'] = $this->newContactEmail;
            $data['contact_phone'] = $this->newContactPhone;
            $data['website'] = $this->newWebsite;
        }        $model::create($data);
        
        $this->resetModal();
        session()->flash('message', 'Eintrag erfolgreich hinzugefügt.');
    }

    public function update()
    {
        $validationRules = ['editingValue' => 'required|string|max:255'];
        $validationMessages = [
            'editingValue.required' => 'Der Wert muss ausgefüllt werden.',
            'editingValue.max' => 'Der Wert darf maximal 255 Zeichen lang sein.',
        ];
        
        // Add validation for model-specific fields
        if (in_array($this->activeTab, ['operating-rooms', 'departments'])) {
            $validationRules['editingCode'] = 'required|string|max:50';
            $validationRules['editingLocation'] = 'required|string|max:255';
            $validationMessages['editingCode.required'] = 'Der Code muss ausgefüllt werden.';
            $validationMessages['editingLocation.required'] = 'Der Standort muss ausgefüllt werden.';
        }
        
        if ($this->activeTab === 'operating-rooms') {
            $validationRules['editingDepartmentId'] = 'required|integer|exists:departments,id';
            $validationMessages['editingDepartmentId.required'] = 'Die Abteilung muss ausgewählt werden.';
        }
        
        $this->validate($validationRules, $validationMessages);
        
        $model = $this->getActiveModel();
        $item = $model::find($this->editingItem);
        
        if (!$item) {
            $this->addError('editingValue', 'Eintrag nicht gefunden.');
            return;
        }
        
        // Check for duplicate names (excluding current item)
        if ($model::where('name', $this->editingValue)->where('id', '!=', $this->editingItem)->exists()) {
            $this->addError('editingValue', 'Dieser Wert existiert bereits.');
            return;
        }
        
        $data = [
            'name' => $this->editingValue,
            'description' => $this->editingDescription,
        ];

        // Add sort_order only for models that have it
        if (!in_array($this->activeTab, ['operating-rooms', 'departments'])) {
            $data['sort_order'] = $this->editingSortOrder;
        }

        // Add model-specific fields
        if (in_array($this->activeTab, ['instrument-statuses', 'container-statuses', 'purchase-order-statuses'])) {
            $data['color'] = $this->editingColor;
        }

        if ($this->activeTab === 'defect-types') {
            $data['severity'] = $this->editingSeverity;
        }

        if (in_array($this->activeTab, ['operating-rooms', 'departments'])) {
            $data['code'] = $this->editingCode;
            $data['location'] = $this->editingLocation;
        }

        if ($this->activeTab === 'operating-rooms') {
            $data['department_id'] = $this->editingDepartmentId;
        }

        if ($this->activeTab === 'manufacturers') {
            $data['contact_person'] = $this->editingContactPerson;
            $data['contact_email'] = $this->editingContactEmail;
            $data['contact_phone'] = $this->editingContactPhone;
            $data['website'] = $this->editingWebsite;
        }        $item->update($data);
        
        $this->resetModal();
        session()->flash('message', 'Eintrag erfolgreich aktualisiert.');
    }

    public function delete()
    {
        $model = $this->getActiveModel();
        $item = $model::find($this->deleteItem['id']);
        
        if ($item) {
            // Check if item is being used
            $usageCount = $this->checkUsage($item);
            if ($usageCount > 0) {
                session()->flash('error', "Dieser Eintrag kann nicht gelöscht werden, da er von {$usageCount} anderen Datensätzen verwendet wird.");
                $this->resetModal();
                return;
            }
            
            $item->delete();
        }
        
        $this->resetModal();
        session()->flash('message', 'Eintrag erfolgreich gelöscht.');
    }

    public function toggleStatus($id)
    {
        $model = $this->getActiveModel();
        $item = $model::find($id);
        
        if ($item) {
            $item->is_active = !$item->is_active;
            $item->save();
            
            $status = $item->is_active ? 'aktiviert' : 'deaktiviert';
            session()->flash('message', "Eintrag erfolgreich {$status}.");
        }
    }

    private function getActiveModel()
    {
        return match ($this->activeTab) {
            'instrument-categories' => InstrumentCategory::class,
            'instrument-statuses' => InstrumentStatus::class,
            'container-types' => ContainerType::class,
            'container-statuses' => ContainerStatus::class,
            'defect-types' => DefectType::class,
            'purchase-order-statuses' => PurchaseOrderStatus::class,
            'manufacturers' => Manufacturer::class,
            'operating-rooms' => OperatingRoom::class,
            'departments' => Department::class,
            default => InstrumentCategory::class
        };
    }

    private function checkUsage($item)
    {
        return match ($this->activeTab) {
            'instrument-categories' => $item->instruments()->count(),
            'instrument-statuses' => $item->instruments()->count(),
            'container-types' => $item->containers()->count(),
            'container-statuses' => $item->containers()->count(),
            'defect-types' => $item->defectReports()->count(),
            'purchase-order-statuses' => $item->purchaseOrders()->count(),
            'manufacturers' => $item->instruments()->count() + $item->purchaseOrders()->count(),
            'operating-rooms' => $item->defectReports()->count(),
            'departments' => $item->operatingRooms()->count(),
            default => 0
        };
    }

    public function getActiveData()
    {
        $model = $this->getActiveModel();
        // Zeige ALLE Einträge (aktiv und inaktiv) für bessere Verwaltung
        $query = $model::query();
        
        // Different ordering for different models
        if (in_array($this->activeTab, ['operating-rooms', 'departments'])) {
            return $query->orderBy('is_active', 'desc')->orderBy('name')->get();
        } else {
            return $query->orderBy('is_active', 'desc')->orderBy('sort_order')->orderBy('name')->get();
        }
    }

    public function getActiveTitle()
    {
        return match ($this->activeTab) {
            'instrument-categories' => 'Instrument-Kategorien',
            'instrument-statuses' => 'Instrument-Status',
            'container-types' => 'Container-Arten',
            'container-statuses' => 'Container-Status',
            'defect-types' => 'Defekt-Arten',
            'purchase-order-statuses' => 'Bestellstatus',
            'manufacturers' => 'Hersteller',
            'operating-rooms' => 'OP-Säle',
            'departments' => 'Abteilungen',
            default => 'Einstellungen'
        };
    }
    
    public function getDepartments()
    {
        return Department::active()->orderBy('name')->get();
    }
}
