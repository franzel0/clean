<?php

namespace App\Livewire\Movements;

use App\Models\InstrumentMovement;
use App\Models\Instrument;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Bewegungshistorie')]
class MovementsList extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $instrumentFilter = '';
    public $startDate = '';
    public $endDate = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingInstrumentFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = InstrumentMovement::query()
            ->with([
                'instrument',
                'fromDepartment',
                'toDepartment',
                'fromContainer',
                'toContainer',
                'performedBy'
            ])
            ->orderBy('performed_at', 'desc');

        // Filter by search term
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('instrument', function ($subQ) {
                    $subQ->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('serial_number', 'like', '%' . $this->search . '%');
                })
                ->orWhere('notes', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by movement type
        if ($this->typeFilter) {
            $query->where('movement_type', $this->typeFilter);
        }

        // Filter by instrument
        if ($this->instrumentFilter) {
            $query->where('instrument_id', $this->instrumentFilter);
        }

        // Filter by date range
        if ($this->startDate) {
            $query->whereDate('performed_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('performed_at', '<=', $this->endDate);
        }

        $movements = $query->paginate(20);

        // Get available movement types for filter
        $movementTypes = InstrumentMovement::select('movement_type')
            ->distinct()
            ->pluck('movement_type')
            ->toArray();

        // Get instruments for filter (recent ones)
        $instruments = Instrument::select('id', 'name', 'serial_number')
            ->whereHas('movements')
            ->orderBy('name')
            ->limit(50)
            ->get();

        return view('livewire.movements.movements-list', [
            'movements' => $movements,
            'movementTypes' => $movementTypes,
            'instruments' => $instruments,
        ]);
    }
}
