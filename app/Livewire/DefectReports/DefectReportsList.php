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
    public $severityFilter = '';
    public $departmentFilter = '';
    public $statusFilter = '';
    public $completionFilter = 'active';
    public $sortBy = 'reported_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'severityFilter', 'departmentFilter', 'statusFilter', 'completionFilter', 'sortBy', 'sortDirection'];

    public function updatingSearch()
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

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCompletionFilter()
    {
        $this->resetPage();
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function acknowledgeReport($reportId)
    {
        $report = DefectReport::findOrFail($reportId);
        $report->update([
            'acknowledged_at' => now(),
            'acknowledged_by' => Auth::user()->id,
        ]);

        session()->flash('message', 'Meldung wurde bestätigt.');
    }

    public function createPurchaseOrder($reportId)
    {
        $report = DefectReport::findOrFail($reportId);
        
        // Generate order number
        $orderNumber = 'PO-' . date('Y') . '-' . str_pad(
            PurchaseOrder::whereYear('created_at', date('Y'))->count() + 1,
            6,
            '0',
            STR_PAD_LEFT
        );
        
        PurchaseOrder::create([
            'order_number' => $orderNumber,
            'defect_report_id' => $reportId,
            'ordered_by' => Auth::user()->id,
            'order_date' => now()->format('Y-m-d'),
            'notes' => 'Automatisch erstellt durch Defektbericht #' . $report->id,
        ]);

        session()->flash('message', 'Bestellung wurde erstellt.');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->severityFilter = '';
        $this->departmentFilter = '';
        $this->completionFilter = 'active';
        $this->resetPage();
    }

    public function render()
    {
        $query = DefectReport::with([
            'instrument.instrumentStatus', 
            'reportedBy', 
            'reportingDepartment',
            'operatingRoom',
            'defectType',
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
        ->when($this->severityFilter, function ($query) {
            $query->where('severity', $this->severityFilter);
        })
        ->when($this->departmentFilter, function ($query) {
            $query->where('reporting_department_id', $this->departmentFilter);
        })
        ->when($this->statusFilter, function ($query) {
            $query->whereHas('instrument', function ($q) {
                $q->where('status_id', $this->statusFilter);
            });
        })
        ->when($this->completionFilter, function ($query) {
            if ($this->completionFilter === 'active') {
                $query->where('is_completed', false);
            } elseif ($this->completionFilter === 'completed') {
                $query->where('is_completed', true);
            }
        });

        $reports = $query->orderBy($this->sortBy, $this->sortDirection)->paginate(20);

        $severities = ['niedrig', 'mittel', 'hoch', 'kritisch'];
        $departments = \App\Models\Department::active()->get();
        $statuses = \App\Models\InstrumentStatus::active()->get();

        return view('livewire.defect-reports.defect-reports-list', compact(
            'reports',
            'severities',
            'departments',
            'statuses'
        ));
    }
}
