<?php

namespace App\Livewire\Reports;

use App\Models\Instrument;
use App\Models\DefectReport;
use App\Models\PurchaseOrder;
use App\Models\Container;
use App\Models\InstrumentMovement;
use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
#[Title('Berichte & Statistiken')]
class ReportsIndex extends Component
{
    public $selectedPeriod = '30';
    public $selectedDepartment = '';

    public function render()
    {
        try {
            $startDate = Carbon::now()->subDays((int)$this->selectedPeriod);
            
            // Basic Statistics
            $totalInstruments = Instrument::count();
            $activeInstruments = Instrument::where('is_active', true)->count();
            $defectiveInstruments = Instrument::where('status', 'defective')->count();
            $inRepairInstruments = Instrument::where('status', 'in_repair')->count();
            
            // Container Statistics
            $totalContainers = Container::count();
            $activeContainers = Container::where('is_active', true)->count();
            
            // Defect Reports Statistics
            $totalDefectReports = DefectReport::count();
            $openDefectReports = DefectReport::where('status', 'reported')->count();
            $recentDefectReports = DefectReport::where('reported_at', '>=', $startDate)->count();
            
            // Purchase Orders Statistics
            $totalPurchaseOrders = PurchaseOrder::count();
            $pendingPurchaseOrders = PurchaseOrder::whereIn('status', ['pending', 'approved', 'ordered'])->count();
            $recentPurchaseOrders = PurchaseOrder::where('created_at', '>=', $startDate)->count();
            
            // Movement Statistics  
            $totalMovements = InstrumentMovement::count();
            $recentMovements = InstrumentMovement::where('moved_at', '>=', $startDate)->count();
            
            // Recent Activity (limited to prevent memory issues)
            $recentDefects = DefectReport::with(['instrument', 'reportedBy'])
                ->when($this->selectedDepartment, function($query) {
                    $query->where('reporting_department_id', $this->selectedDepartment);
                })
                ->orderBy('reported_at', 'desc')
                ->limit(5)
                ->get();
                
            $recentMovementsList = InstrumentMovement::with(['instrument', 'movedBy'])
                ->when($this->selectedDepartment, function($query) {
                    $query->where('from_department_id', $this->selectedDepartment)
                         ->orWhere('to_department_id', $this->selectedDepartment);
                })
                ->orderBy('moved_at', 'desc')
                ->limit(5)
                ->get();

            // Simple Status Distribution
            $statusDistribution = collect([
                'available' => Instrument::where('status', 'available')->count(),
                'in_use' => Instrument::where('status', 'in_use')->count(),
                'defective' => Instrument::where('status', 'defective')->count(),
                'in_repair' => Instrument::where('status', 'in_repair')->count(),
                'out_of_service' => Instrument::where('status', 'out_of_service')->count(),
            ])->map(function($count, $status) {
                return (object)['count' => $count, 'status' => $status];
            })->keyBy('status');

            // Simple Defect Type Distribution
            $defectTypeDistribution = collect([
                'broken' => DefectReport::where('defect_type', 'broken')->where('reported_at', '>=', $startDate)->count(),
                'dull' => DefectReport::where('defect_type', 'dull')->where('reported_at', '>=', $startDate)->count(),
                'bent' => DefectReport::where('defect_type', 'bent')->where('reported_at', '>=', $startDate)->count(),
                'missing_parts' => DefectReport::where('defect_type', 'missing_parts')->where('reported_at', '>=', $startDate)->count(),
                'other' => DefectReport::where('defect_type', 'other')->where('reported_at', '>=', $startDate)->count(),
            ])->map(function($count, $type) {
                return (object)['count' => $count, 'defect_type' => $type];
            })->keyBy('defect_type');

            // Simple Top Defective Instruments (avoid complex queries)
            $topDefectiveInstruments = collect();

            $departments = Department::all();

            return view('livewire.reports.reports-index', [
                'totalInstruments' => $totalInstruments,
                'activeInstruments' => $activeInstruments,
                'defectiveInstruments' => $defectiveInstruments,
                'inRepairInstruments' => $inRepairInstruments,
                'totalContainers' => $totalContainers,
                'activeContainers' => $activeContainers,
                'totalDefectReports' => $totalDefectReports,
                'openDefectReports' => $openDefectReports,
                'recentDefectReports' => $recentDefectReports,
                'totalPurchaseOrders' => $totalPurchaseOrders,
                'pendingPurchaseOrders' => $pendingPurchaseOrders,
                'recentPurchaseOrders' => $recentPurchaseOrders,
                'totalMovements' => $totalMovements,
                'recentMovements' => $recentMovements,
                'recentDefects' => $recentDefects,
                'recentMovementsList' => $recentMovementsList,
                'statusDistribution' => $statusDistribution,
                'defectTypeDistribution' => $defectTypeDistribution,
                'topDefectiveInstruments' => $topDefectiveInstruments,
                'departments' => $departments,
            ]);
            
        } catch (\Exception $e) {
            // Log error and show simple fallback
            \Illuminate\Support\Facades\Log::error('Reports error: ' . $e->getMessage());
            
            return view('livewire.reports.reports-index', [
                'totalInstruments' => 0,
                'activeInstruments' => 0,
                'defectiveInstruments' => 0,
                'inRepairInstruments' => 0,
                'totalContainers' => 0,
                'activeContainers' => 0,
                'totalDefectReports' => 0,
                'openDefectReports' => 0,
                'recentDefectReports' => 0,
                'totalPurchaseOrders' => 0,
                'pendingPurchaseOrders' => 0,
                'recentPurchaseOrders' => 0,
                'totalMovements' => 0,
                'recentMovements' => 0,
                'recentDefects' => collect(),
                'recentMovementsList' => collect(),
                'statusDistribution' => collect(),
                'defectTypeDistribution' => collect(),
                'topDefectiveInstruments' => collect(),
                'departments' => collect(),
            ]);
        }
    }
}
