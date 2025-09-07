<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Instrument;
use App\Models\DefectReport;
use App\Models\PurchaseOrder;
use App\Models\InstrumentStatus;
use App\Services\InstrumentStatusService;

class MigrateStatusData extends Command
{
    protected $signature = 'status:migrate
                          {--dry-run : Zeige nur was geändert werden würde}';
    
    protected $description = 'Migriert bestehende Status-Daten zum neuen System';

    protected $statusService;

    public function __construct(InstrumentStatusService $statusService)
    {
        parent::__construct();
        $this->statusService = $statusService;
    }

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN - Keine Änderungen werden vorgenommen');
        }

        $this->info('📊 Analysiere bestehende Daten...');

        // 1. Migriere Instrument-Status
        $this->migrateInstrumentStatuses($dryRun);

        // 2. Setze Status basierend auf offenen Defektmeldungen
        $this->updateStatusFromDefectReports($dryRun);

        // 3. Setze Status basierend auf offenen Bestellungen
        $this->updateStatusFromPurchaseOrders($dryRun);

        $this->info('✅ Status-Migration abgeschlossen!');
    }

    protected function migrateInstrumentStatuses($dryRun)
    {
        $this->info('🔧 Migriere Instrument-Status...');

        $instruments = Instrument::with('instrumentStatus')->get();
        $migrated = 0;

        foreach ($instruments as $instrument) {
            $currentStatus = $instrument->instrumentStatus?->name;
            $newStatus = $this->mapOldStatusToNew($currentStatus);

            if ($newStatus && $newStatus !== $currentStatus) {
                if (!$dryRun) {
                    $this->statusService->setInstrumentStatus($instrument, $newStatus);
                }
                
                $this->line("  {$instrument->name}: '{$currentStatus}' → '{$newStatus}'");
                $migrated++;
            }
        }

        $this->info("   {$migrated} Instrumente migriert");
    }

    protected function updateStatusFromDefectReports($dryRun)
    {
        $this->info('🚨 Analysiere offene Defektmeldungen...');

        $openDefectReports = DefectReport::where('is_resolved', false)
            ->with('instrument')
            ->get();

        foreach ($openDefectReports as $defectReport) {
            $instrument = $defectReport->instrument;
            
            if (!$dryRun) {
                $this->statusService->setInstrumentStatus($instrument, 'Defekt gemeldet');
            }
            
            $this->line("  {$instrument->name}: Status → 'Defekt gemeldet'");
        }

        $this->info("   {$openDefectReports->count()} Instrumente als 'Defekt gemeldet' markiert");
    }

    protected function updateStatusFromPurchaseOrders($dryRun)
    {
        $this->info('📦 Analysiere offene Bestellungen...');

        $openOrders = PurchaseOrder::where('is_delivered', false)
            ->with('defectReport.instrument')
            ->whereNotNull('defect_report_id')
            ->get();

        $count = 0;
        foreach ($openOrders as $order) {
            if ($order->defectReport && $order->defectReport->instrument) {
                $instrument = $order->defectReport->instrument;
                
                if (!$dryRun) {
                    $this->statusService->setInstrumentStatus($instrument, 'Ersatz bestellt');
                }
                
                $this->line("  {$instrument->name}: Status → 'Ersatz bestellt'");
                $count++;
            }
        }

        $this->info("   {$count} Instrumente als 'Ersatz bestellt' markiert");
    }

    protected function mapOldStatusToNew($oldStatus)
    {
        $mapping = [
            'Aktiv' => 'Verfügbar',
            'Inaktiv' => 'Aussortiert',
            'Defekt' => 'Defekt bestätigt',
            'Wartung' => 'In Wartung',
            'Reparatur' => 'In Reparatur',
            'Außer Betrieb' => 'Aussortiert',
        ];

        return $mapping[$oldStatus] ?? null;
    }
}
