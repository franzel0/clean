<?php

namespace App\Livewire\Reports;

use App\Models\Instrument;
use App\Models\DefectReport;
use App\Models\Container;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Berichte & Statistiken')]
class SimpleReports extends Component
{
    public function render()
    {
        try {
            // Basic statistics
            $totalInstruments = Instrument::count();
            $functionalInstruments = Instrument::where('is_active', true)
                ->whereIn('status', ['available', 'in_use', 'active'])
                ->count();
            $defectiveInstruments = Instrument::where('status', 'defective')->count();
            $inRepairInstruments = Instrument::where('status', 'in_repair')->count();
            $availableInstruments = Instrument::whereIn('status', ['available', 'active'])->count();
            
            $totalDefectReports = DefectReport::count();
            $openDefectReports = DefectReport::where('status', 'reported')->count();
            $recentDefectReports = DefectReport::where('reported_at', '>=', now()->subDays(30))->count();
            
            $totalContainers = Container::count();
            $activeContainers = Container::where('is_active', true)->count();
            
            // Container status based on calculated status using InstrumentStatusService
            $statusService = app(\App\Services\InstrumentStatusService::class);
            $containers = Container::where('is_active', true)->with(['instruments.instrumentStatus'])->get();
            
            $completeContainers = 0;
            $incompleteContainers = 0;
            $outOfServiceContainers = 0;
            
            foreach ($containers as $container) {
                $calculatedStatus = $statusService->calculateContainerStatus($container);
                
                switch ($calculatedStatus) {
                    case 'Vollständig & betriebsbereit':
                        $completeContainers++;
                        break;
                    case 'Unvollständig aber betriebsbereit':
                        $incompleteContainers++;
                        break;
                    case 'Nicht betriebsbereit':
                        $outOfServiceContainers++;
                        break;
                }
            }
            
            // Status distribution
            $statusStats = [
                'available' => Instrument::whereIn('status', ['available', 'active'])->count(),
                'in_use' => Instrument::where('status', 'in_use')->count(),
                'defective' => Instrument::where('status', 'defective')->count(),
                'in_repair' => Instrument::where('status', 'in_repair')->count(),
                'out_of_service' => Instrument::where('status', 'out_of_service')->count(),
            ];
            
            // Recent defects (last 5)
            $recentDefects = DefectReport::with(['instrument', 'reportedBy'])
                ->orderBy('reported_at', 'desc')
                ->limit(5)
                ->get();
            
            return view('livewire.reports.simple-reports', [
                'totalInstruments' => $totalInstruments,
                'functionalInstruments' => $functionalInstruments,
                'defectiveInstruments' => $defectiveInstruments,
                'inRepairInstruments' => $inRepairInstruments,
                'availableInstruments' => $availableInstruments,
                'totalDefectReports' => $totalDefectReports,
                'openDefectReports' => $openDefectReports,
                'recentDefectReports' => $recentDefectReports,
                'totalContainers' => $totalContainers,
                'activeContainers' => $activeContainers,
                'completeContainers' => $completeContainers,
                'incompleteContainers' => $incompleteContainers,
                'outOfServiceContainers' => $outOfServiceContainers,
                'statusStats' => $statusStats,
                'recentDefects' => $recentDefects,
            ]);
            
        } catch (\Exception $e) {
            return view('livewire.reports.simple-reports', [
                'totalInstruments' => 0,
                'functionalInstruments' => 0,
                'defectiveInstruments' => 0,
                'inRepairInstruments' => 0,
                'availableInstruments' => 0,
                'totalDefectReports' => 0,
                'openDefectReports' => 0,
                'recentDefectReports' => 0,
                'totalContainers' => 0,
                'activeContainers' => 0,
                'completeContainers' => 0,
                'incompleteContainers' => 0,
                'outOfServiceContainers' => 0,
                'statusStats' => [],
                'recentDefects' => collect(),
                'error' => $e->getMessage(),
            ]);
        }
    }
}
