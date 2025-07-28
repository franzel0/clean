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
    ];

    public function mount()
    {
        $this->authorize('viewAny', \App\Models\User::class);
    }

    public function render()
    {
        $operatingRooms = OperatingRoom::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        
        $data = [];
        if (in_array($this->activeTab, ['instrument-categories', 'instrument-statuses', 'container-types', 'container-statuses', 'defect-types', 'purchase-order-statuses'])) {
            $data = $this->getActiveData();
        }
        
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
            $this->editingSortOrder = $item->sort_order ?? 0;
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
        $this->newValue = '';
        $this->newDescription = '';
        $this->newColor = 'gray';
        $this->newSeverity = 'medium';
        $this->newSortOrder = 0;
        $this->deleteItem = null;
        $this->resetValidation();
    }

    public function create()
    {
        $this->validate(['newValue' => 'required|string|max:255'], [
            'newValue.required' => 'Der Wert muss ausgefüllt werden.',
            'newValue.max' => 'Der Wert darf maximal 255 Zeichen lang sein.',
        ]);
        
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
            'sort_order' => $this->newSortOrder ?: $model::max('sort_order') + 1,
        ];
        
        // Add model-specific fields
        if (in_array($this->activeTab, ['instrument-statuses', 'container-statuses', 'purchase-order-statuses'])) {
            $data['color'] = $this->newColor;
        }
        
        if ($this->activeTab === 'defect-types') {
            $data['severity'] = $this->newSeverity;
        }
        
        $model::create($data);
        
        $this->resetModal();
        session()->flash('message', 'Eintrag erfolgreich hinzugefügt.');
    }

    public function update()
    {
        $this->validate(['editingValue' => 'required|string|max:255'], [
            'editingValue.required' => 'Der Wert muss ausgefüllt werden.',
            'editingValue.max' => 'Der Wert darf maximal 255 Zeichen lang sein.',
        ]);
        
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
            'sort_order' => $this->editingSortOrder,
        ];
        
        // Add model-specific fields
        if (in_array($this->activeTab, ['instrument-statuses', 'container-statuses', 'purchase-order-statuses'])) {
            $data['color'] = $this->editingColor;
        }
        
        if ($this->activeTab === 'defect-types') {
            $data['severity'] = $this->editingSeverity;
        }
        
        $item->update($data);
        
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
            default => 0
        };
    }

    public function getActiveData()
    {
        $model = $this->getActiveModel();
        return $model::active()->ordered()->get();
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
}
