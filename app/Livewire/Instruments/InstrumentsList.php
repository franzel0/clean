<?php

namespace App\Livewire\Instruments;

use App\Models\Instrument;
use App\Models\Container;
use App\Models\Department;
use App\Models\InstrumentStatus;
use App\Models\InstrumentCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class InstrumentsList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $categoryFilter = '';
    public $containerFilter = '';
    public $activeOnly = true; // Default to show only active instruments
    
    protected $queryString = ['search', 'statusFilter', 'categoryFilter', 'containerFilter', 'activeOnly'];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingContainerFilter()
    {
        $this->resetPage();
    }

    public function updatedContainerFilter()
    {
        $this->resetPage();
    }

    public function updatingActiveOnly()
    {
        $this->resetPage();
    }

    public function updatedActiveOnly()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->categoryFilter = '';
        $this->containerFilter = '';
        $this->activeOnly = true; // Reset to default (show only active)
        $this->resetPage();
    }

    public function createInstrument()
    {
        return redirect()->route('instruments.create');
    }

    public function editInstrument($instrumentId)
    {
        return redirect()->route('instruments.edit', $instrumentId);
    }

    public function deleteInstrument($instrumentId)
    {
        try {
            $instrument = Instrument::findOrFail($instrumentId);
            
            // Check for foreign key constraints
            $hasDefectReports = $instrument->defectReports()->exists();
            $hasMovements = $instrument->movements()->exists();
            $isAssignedToContainer = !is_null($instrument->current_container_id);
            
            if ($hasDefectReports || $hasMovements || $isAssignedToContainer) {
                // Find the "out_of_service" status ID
                $outOfServiceStatus = \App\Models\InstrumentStatus::where('name', 'Außer Betrieb')
                    ->orWhere('name', 'Out of Service')
                    ->orWhere('slug', 'out_of_service')
                    ->first();
                
                if (!$outOfServiceStatus) {
                    // Fallback: find any inactive/disabled status or create one
                    $outOfServiceStatus = \App\Models\InstrumentStatus::firstOrCreate([
                        'name' => 'Außer Betrieb',
                        'slug' => 'out_of_service',
                        'color' => '#6B7280',
                        'is_active' => true,
                        'sort_order' => 999
                    ]);
                }
                
                // Instead of deleting, deactivate the instrument
                $instrument->update([
                    'is_active' => false,
                    'status_id' => $outOfServiceStatus->id,
                    'current_container_id' => null // Remove from container if assigned
                ]);
                
                $constraints = [];
                if ($hasDefectReports) $constraints[] = 'Defektmeldungen';
                if ($hasMovements) $constraints[] = 'Bewegungsdaten';
                if ($isAssignedToContainer) $constraints[] = 'Container-Zuweisungen';
                
                session()->flash('warning', 
                    'Instrument konnte nicht gelöscht werden aufgrund bestehender ' . implode(', ', $constraints) . 
                    '. Das Instrument wurde stattdessen deaktiviert und als "Außer Betrieb" markiert.'
                );
            } else {
                // Safe to delete - no foreign key constraints
                $instrument->delete();
                session()->flash('message', 'Instrument erfolgreich gelöscht.');
            }
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error deleting instrument: ' . $e->getMessage());
            session()->flash('error', 'Fehler beim Löschen des Instruments: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Instrument::with(['currentContainer', 'currentLocation', 'category', 'instrumentStatus', 'manufacturerRelation']);
        
        // Debugging: Log current filter values
        Log::info('Filter values', [
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'categoryFilter' => $this->categoryFilter,
            'containerFilter' => $this->containerFilter,
            'activeOnly' => $this->activeOnly
        ]);
        
        // Filter by active status
        if ($this->activeOnly) {
            $query->where('is_active', true);
        }
        
        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('serial_number', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhereHas('manufacturerRelation', function($mq) use ($search) {
                      $mq->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('category', function($cq) use ($search) {
                      $cq->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('currentContainer', function($ccq) use ($search) {
                      $ccq->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('currentLocation', function($clq) use ($search) {
                      $clq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        if (!empty($this->statusFilter)) {
            $query->where('status_id', $this->statusFilter);
        }
        
        if (!empty($this->categoryFilter)) {
            $query->where('category_id', $this->categoryFilter);
        }
        
        if (!empty($this->containerFilter)) {
            $query->where('current_container_id', $this->containerFilter);
        }

        $instruments = $query->orderBy('name')->paginate(20);

        $containers = Container::all();
        $statuses = InstrumentStatus::active()->ordered()->get();
        $categories = InstrumentCategory::active()->ordered()->get();

        return view('livewire.instruments.instruments-list', compact(
            'instruments', 
            'containers', 
            'statuses', 
            'categories'
        ));
    }
}
