<?php

namespace App\Observers;

use App\Models\DefectReport;
use App\Services\InstrumentStatusService;

class DefectReportObserver
{
    protected $statusService;

    public function __construct(InstrumentStatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    /**
     * Handle the DefectReport "created" event.
     */
    public function created(DefectReport $defectReport): void
    {
        $this->statusService->updateStatusOnDefectReport($defectReport, 'created');
    }

    /**
     * Handle the DefectReport "updated" event.
     */
    public function updated(DefectReport $defectReport): void
    {
        // Wenn die Defektmeldung als gelöst markiert wird
        if ($defectReport->wasChanged('is_resolved') && $defectReport->is_resolved) {
            $this->statusService->updateStatusOnDefectReport($defectReport, 'resolved');
        }
    }
}
