<?php

namespace App\Livewire;

use App\Models\DefectReport;
use App\Models\Department;
use App\Models\Instrument;
use App\Models\PurchaseOrder;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_instruments' => Instrument::active()->count(),
            'defective_instruments' => Instrument::defective()->count(),
            'open_reports' => DefectReport::open()->count(),
            'pending_orders' => PurchaseOrder::open()->count(),
        ];

        $recent_reports = DefectReport::with(['instrument', 'reportedBy', 'reportingDepartment'])
            ->latest()
            ->take(5)
            ->get();

        $instruments_by_status = Instrument::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $reports_by_department = DefectReport::join('departments', 'defect_reports.reporting_department_id', '=', 'departments.id')
            ->selectRaw('departments.name, count(*) as count')
            ->groupBy('departments.id', 'departments.name')
            ->get()
            ->pluck('count', 'name');

        return view('livewire.dashboard', compact('stats', 'recent_reports', 'instruments_by_status', 'reports_by_department'));
    }
}
