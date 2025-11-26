<?php

namespace App\Livewire\DefectReports;

use App\Models\DefectReport;
use App\Models\InstrumentMovement;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Defektmeldung anzeigen')]
class ShowDefectReport extends Component
{
    public DefectReport $report;

    public function mount(DefectReport $report)
    {
        $this->report = $report->load(['defectType', 'instrument.instrumentStatus', 'instrument.currentLocation', 'reportedBy', 'reportingDepartment', 'resolvedBy', 'operatingRoom']);
    }

    public function render()
    {
        // Hole alle Bewegungen für dieses Instrument ab dem Zeitpunkt der Defektmeldung
        $movements = InstrumentMovement::where('instrument_id', $this->report->instrument_id)
            ->where('performed_at', '>=', $this->report->created_at)
            ->where('movement_type', 'status_change')
            ->with(['performedBy', 'statusBeforeObject', 'statusAfterObject'])
            ->orderBy('performed_at', 'desc')
            ->get();
        
        return view('livewire.defect-reports.show-defect-report', [
            'movements' => $movements
        ]);
    }
}

