<?php

namespace App\Livewire\DefectReports;

use App\Models\DefectReport;
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
        $this->report = $report->load(['defectType', 'instrument', 'reportedBy', 'reportingDepartment']);
    }

    public function render()
    {
        return view('livewire.defect-reports.show-defect-report');
    }
}
