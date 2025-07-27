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

        $containers = Container::active()->get();
        $statuses = ['available', 'in_use', 'defective', 'in_repair', 'out_of_service'];
        $categories = ['scissors', 'forceps', 'scalpel', 'clamp', 'retractor', 'needle_holder'];

        return view('livewire.instruments.instruments-list', compact(
            'instruments', 
            'containers', 
            'statuses', 
            'categories'
        ));
    }
}
