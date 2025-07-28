<?php

namespace App\Livewire\Instruments;

use App\Models\Instrument;
use App\Models\Container;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class InstrumentsList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $categoryFilter = '';
    public $containerFilter = '';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingContainerFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->categoryFilter = '';
        $this->containerFilter = '';
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
        $instrument = Instrument::findOrFail($instrumentId);
        $instrument->delete();
        session()->flash('message', 'Instrument erfolgreich gelöscht.');
    }

    public function render()
    {
        $query = Instrument::with(['currentContainer', 'currentLocation'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('serial_number', 'like', '%' . $this->search . '%')
                      ->orWhere('manufacturer', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category', $this->categoryFilter);
            })
            ->when($this->containerFilter, function ($query) {
                $query->where('current_container_id', $this->containerFilter);
            });

        $instruments = $query->paginate(20);

        $containers = Container::all();
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

        return view('livewire.instruments.instruments-list', compact(
            'instruments', 
            'containers', 
            'statuses', 
            'categories'
        ));
    }
}
