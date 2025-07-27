<?php

namespace App\Livewire\Instruments;

use App\Models\Instrument;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Instrument anzeigen')]
class ShowInstrument extends Component
{
    public Instrument $instrument;

    public function mount(Instrument $instrument)
    {
        $this->instrument = $instrument->load([
            'currentContainer',
            'defectReports.reportedBy',
            'defectReports.reportingDepartment',
            'movements.fromDepartment',
            'movements.toDepartment', 
            'movements.fromContainer',
            'movements.toContainer',
            'movements.movedBy'
        ]);
    }

    public function render()
    {
        return view('livewire.instruments.show-instrument');
    }
}
