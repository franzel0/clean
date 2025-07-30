<?php

namespace App\Livewire\Containers;

use App\Models\Container;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Container')]
class ContainersList extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->typeFilter = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Container::query()
            ->with(['instruments.instrumentStatus'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('barcode', 'like', '%' . $this->search . '%');
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            });

        $containers = $query->latest()->paginate(15);

        $types = ['surgical_set', 'basic_set', 'special_set'];

        return view('livewire.containers.containers-list', compact(
            'containers',
            'types'
        ));
    }
}
