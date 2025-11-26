<?php

namespace App\Services;

use App\Models\Instrument;
use App\Models\DefectReport;
use App\Models\PurchaseOrder;
use App\Models\InstrumentStatus;
use Illuminate\Support\Facades\DB;

class InstrumentStatusService
{
    /**
     * Aktualisiere Instrument-Status basierend auf Workflow-Ereignissen
     */
    public function updateStatusOnDefectReport(DefectReport $defectReport, string $action): void
    {
        $instrument = $defectReport->instrument;
        
        switch ($action) {
            case 'created':
                $this->setInstrumentStatus($instrument, 'Defekt gemeldet');
                break;
                
            case 'confirmed':
                $this->setInstrumentStatus($instrument, 'Defekt bestätigt');
                break;
                
            case 'resolved':
                // Prüfe ob Ersatz bestellt wurde - über DefectReports
                $hasPendingOrder = PurchaseOrder::whereHas('defectReport', function($query) use ($instrument) {
                    $query->where('instrument_id', $instrument->id);
                })
                ->whereNull('received_at')
                ->exists();
                    
                if ($hasPendingOrder) {
                    $this->setInstrumentStatus($instrument, 'Ersatz bestellt');
                } else {
                    $this->setInstrumentStatus($instrument, 'Repariert');
                }
                break;
        }
    }

    /**
     * Aktualisiere Instrument-Status basierend auf Bestellungen
     */
    public function updateStatusOnPurchaseOrder(PurchaseOrder $purchaseOrder, string $action): void
    {
        // Hole das Instrument über die DefectReport-Beziehung
        if ($purchaseOrder->defectReport && $purchaseOrder->defectReport->instrument) {
            $instrument = $purchaseOrder->defectReport->instrument;
            
            switch ($action) {
                case 'created':
                    $this->setInstrumentStatus($instrument, 'Ersatz bestellt');
                    break;
                    
                case 'delivered':
                    $this->setInstrumentStatus($instrument, 'Ersatz geliefert');
                    break;
            }
        }
    }

    /**
     * Setze Instrument-Status
     */
    public function setInstrumentStatus(Instrument $instrument, string $statusName): void
    {
        $status = InstrumentStatus::where('name', $statusName)->first();
        
        if ($status) {
            $instrument->update(['status_id' => $status->id]);
        }
    }

    /**
     * Hole verfügbare Status-Übergänge für ein Instrument
     */
    public function getAvailableStatusTransitions(Instrument $instrument): array
    {
        $currentStatus = $instrument->instrumentStatus?->name;
        
        $transitions = [
            'Verfügbar' => ['In Betrieb', 'Defekt gemeldet', 'In Wartung', 'Aussortiert'],
            'In Betrieb' => ['Verfügbar', 'Defekt gemeldet', 'In Wartung'],
            'Defekt gemeldet' => ['Defekt bestätigt', 'Verfügbar'], // Falls falscher Alarm
            'Defekt bestätigt' => ['In Reparatur', 'Ersatz bestellt', 'Aussortiert'],
            'Ersatz bestellt' => ['Ersatz geliefert', 'In Reparatur'],
            'Ersatz geliefert' => ['Verfügbar', 'In Betrieb'],
            'In Reparatur' => ['Repariert', 'Aussortiert'],
            'Repariert' => ['Verfügbar', 'In Betrieb'],
            'In Wartung' => ['Verfügbar', 'In Betrieb'],
            'Aussortiert' => ['Verloren/Vermisst'], // Nur bei Verlust
            'Verloren/Vermisst' => [], // Endstatus
        ];
        
        return $transitions[$currentStatus] ?? [];
    }

    /**
     * Prüfe ob Status-Übergang erlaubt ist
     */
    public function canTransitionTo(Instrument $instrument, string $newStatusName): bool
    {
        $availableTransitions = $this->getAvailableStatusTransitions($instrument);
        return in_array($newStatusName, $availableTransitions);
    }

    /**
     * Container-Status basierend auf Instrumenten-Status berechnen
     */
    public function calculateContainerStatus($container): string
    {
        // Eager load the instrumentStatus relationship to ensure the status name is available
        $instruments = $container->instruments()->with('instrumentStatus')->get();
        
        if ($instruments->isEmpty()) {
            return 'Nicht betriebsbereit';
        }
        
        $totalInstruments = $instruments->count();
        $operationalInstruments = $instruments->filter(function ($instrument) {
            return in_array($instrument->instrumentStatus?->name, [
                'Verfügbar', 'In Betrieb', 'Repariert'
            ]);
        })->count();
        
        $defectiveInstruments = $instruments->filter(function ($instrument) {
            return in_array($instrument->instrumentStatus?->name, [
                'Defekt gemeldet', 'Defekt bestätigt', 'In Reparatur', 'Aussortiert', 'Verloren/Vermisst'
            ]);
        })->count();
        
        // Wenn alle Instrumente funktionsfähig sind
        if ($operationalInstruments === $totalInstruments) {
            return 'Vollständig & betriebsbereit';
        }
        
        // Wenn mehr als 80% funktionsfähig sind
        if (($operationalInstruments / $totalInstruments) >= 0.8) {
            return 'Unvollständig aber betriebsbereit';
        }
        
        // Sonst nicht betriebsbereit
        return 'Nicht betriebsbereit';
    }
}
