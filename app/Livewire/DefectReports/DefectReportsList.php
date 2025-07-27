<?php

namespace App\Livewire\DefectReports;

use App\Models\DefectReport;
use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DefectReportsList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $severityFilter = '';
    public $departmentFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSeverityFilter()
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }

    public function acknowledgeReport($reportId)
    {
        $report = DefectReport::findOrFail($reportId);
        $report->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by' => Auth::user()->id,
        ]);

        session()->flash('message', 'Meldung wurde bestätigt.');
    }

    public function createPurchaseOrder($reportId)
    {
        $report = DefectReport::findOrFail($reportId);
        
        PurchaseOrder::create([
            'defect_report_id' => $reportId,
            'requested_by' => Auth::user()->id,
            'status' => 'requested',
            'requested_at' => now(),
        ]);

        $report->update(['status' => 'ordered']);

        session()->flash('message', 'Bestellung wurde erstellt.');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->severityFilter = '';
        $this->departmentFilter = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = DefectReport::with([
            'instrument', 
            'reportedBy', 
            'reportingDepartment',
            'operatingRoom',
            'purchaseOrder'
        ])
        ->when($this->search, function ($query) {
            $query->whereHas('instrument', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $this->search . '%');
            })
            ->orWhere('report_number', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%');
        })
        ->when($this->statusFilter, function ($query) {
            $query->where('status', $this->statusFilter);
        })
        ->when($this->severityFilter, function ($query) {
            $query->where('severity', $this->severityFilter);
        })
        ->when($this->departmentFilter, function ($query) {
            $query->where('reporting_department_id', $this->departmentFilter);
        });

        $reports = $query->latest()->paginate(15);

        $statuses = ['reported', 'acknowledged', 'in_review', 'ordered', 'received', 'repaired', 'closed'];
        $severities = ['low', 'medium', 'high', 'critical'];
        $departments = \App\Models\Department::active()->get();

        return view('livewire.defect-reports.defect-reports-list', compact(
            'reports',
            'statuses',
            'severities',
            'departments'
        ));
    }
}
