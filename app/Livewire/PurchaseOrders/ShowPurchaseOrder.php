<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
use App\Models\Manufacturer;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.app')]
#[Title('Bestellung anzeigen')]
class ShowPurchaseOrder extends Component
{
    public PurchaseOrder $order;
    public $showStatusDropdown = false;
    public $notes = '';
    public $totalAmount = '';
    public $manufacturer_id = '';
    public $expectedDelivery = '';
    public $instrumentStatusId = '';
    public $is_completed = false;
    public $defect_report_completed = false;

    public function mount(PurchaseOrder $order)
    {
        // Authorization: Nur berechtigt Benutzer können Bestellungen anzeigen
        $this->authorize('view', $order);
        
        $this->order = $order->load([
            'defectReport.instrument.instrumentStatus',
            'defectReport.reportedBy',
            'defectReport.reportingDepartment',
            'requestedBy',
            'approvedBy',
            'receivedBy',
            'manufacturer'
        ]);
        
        $this->manufacturer_id = $this->order->manufacturer_id;
        $this->totalAmount = $this->order->total_amount;
        $this->notes = $this->order->notes;
        $this->expectedDelivery = $this->order->expected_delivery;
        $this->instrumentStatusId = $this->order->defectReport?->instrument?->status_id ?? '';
        $this->is_completed = $this->order->is_completed ?? false;
        $this->defect_report_completed = $this->order->defectReport?->is_completed ?? false;
    }

    public function toggleStatusDropdown()
    {
        $this->showStatusDropdown = !$this->showStatusDropdown;
    }

    public function updateDetails()
    {
        // Authorization: Nur berechtigt Benutzer können Bestelldetails ändern
        $this->authorize('update', $this->order);
        
        $this->validate([
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'totalAmount' => 'nullable|numeric|min:0|max:999999.99',
            'expectedDelivery' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:2000',
            'instrumentStatusId' => 'nullable|exists:instrument_statuses,id',
            'is_completed' => 'boolean',
            'defect_report_completed' => 'boolean',
        ], [
            'manufacturer_id.exists' => 'Bitte wählen Sie einen gültigen Hersteller aus.',
            'totalAmount.numeric' => 'Die tatsächlichen Kosten müssen eine Zahl sein.',
            'totalAmount.min' => 'Die tatsächlichen Kosten können nicht negativ sein.',
            'totalAmount.max' => 'Die tatsächlichen Kosten sind zu hoch (max. 999.999,99 €).',
            'expectedDelivery.date' => 'Bitte geben Sie ein gültiges Lieferdatum ein.',
            'expectedDelivery.after_or_equal' => 'Das Lieferdatum kann nicht in der Vergangenheit liegen.',
            'notes.max' => 'Notizen dürfen maximal 2000 Zeichen lang sein.',
            'instrumentStatusId.exists' => 'Bitte wählen Sie einen gültigen Instrumentenstatus aus.',
        ]);

        $this->order->update([
            'manufacturer_id' => $this->manufacturer_id,
            'total_amount' => $this->totalAmount,
            'expected_delivery' => $this->expectedDelivery,
            'notes' => $this->notes,
            'is_completed' => $this->is_completed,
        ]);

        // Instrumentenstatus aktualisieren falls vorhanden
        if ($this->instrumentStatusId && $this->order->defectReport?->instrument) {
            $oldStatusId = $this->order->defectReport->instrument->status_id;
            
            if ($oldStatusId != $this->instrumentStatusId) {
                $this->order->defectReport->instrument->update([
                    'status_id' => $this->instrumentStatusId
                ]);
                
                // Log movement
                \App\Services\MovementService::logMovementOnly(
                    instrument: $this->order->defectReport->instrument,
                    movementType: 'status_change',
                    statusBefore: $oldStatusId,
                    statusAfter: $this->instrumentStatusId,
                    notes: 'Status aktualisiert via Bestellung: ' . $this->order->order_number,
                    movedBy: Auth::user()->id
                );
            }
        }

        // Defektmeldung Status aktualisieren falls vorhanden
        if ($this->order->defectReport) {
            $this->order->defectReport->update([
                'is_completed' => $this->defect_report_completed,
                'resolved_at' => $this->defect_report_completed ? now() : $this->order->defectReport->resolved_at,
                'resolved_by' => $this->defect_report_completed ? Auth::id() : $this->order->defectReport->resolved_by,
            ]);
        }

        // Reload relationships after update to refresh the view
        $this->order->load([
            'manufacturer', 
            'defectReport.instrument.instrumentStatus',
            'defectReport.instrument.movements' // Lade auch die Bewegungen neu
        ]);

        session()->flash('message', 'Bestelldetails erfolgreich aktualisiert.');
    }

    public function getAvailableInstrumentStatusesProperty()
    {
        return \App\Models\InstrumentStatus::availableInPurchaseOrders()->active()->get();
    }

    public function getAvailableStatusTransitions()
    {
        // Lade nur Status, die für Purchase Orders verfügbar sind
        $instrumentStatuses = \App\Models\InstrumentStatus::availableInPurchaseOrders()
            ->active()
            ->orderBy('sort_order')
            ->get();
        
        // Aktueller Instrumentenstatus
        $currentStatusId = $this->order->defectReport?->instrument?->status_id;
        
        $availableTransitions = collect();
        
        foreach ($instrumentStatuses as $status) {
            // Skip den aktuellen Status
            if ($status->id === $currentStatusId) {
                continue;
            }
            
            $availableTransitions->push([
                'id' => $status->id,
                'name' => $status->name,
                'color' => $status->color,
                'bg_class' => $status->bg_class,
                'text_class' => $status->text_class
            ]);
        }
        
        return $availableTransitions;
    }

    public function cancelOrder()
    {
        $this->order->update(['status' => 'cancelled']);
        $this->order->refresh();
        
        session()->flash('message', 'Bestellung storniert.');
    }

    public function downloadPdf()
    {
        // Authorization: Nur berechtigt Benutzer können PDFs herunterladen
        $this->authorize('view', $this->order);
        
        // Vollständig geladene Order für PDF
        $order = PurchaseOrder::with([
            'defectReport.instrument',
            'defectReport.reportedBy',
            'defectReport.reportingDepartment',
            'requestedBy',
            'approvedBy',
            'receivedBy',
            'manufacturer'
        ])->findOrFail($this->order->id);

        $pdf = Pdf::loadView('pdf.purchase-order', ['order' => $order]);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'bestellung-' . $order->order_number . '.pdf');
    }

    public function getStatusDisplayName($statusId)
    {
        $status = \App\Models\InstrumentStatus::find($statusId);
        return $status?->name ?? 'Unbekannt';
    }

    public function render()
    {
        $manufacturers = Manufacturer::active()->ordered()->get();
        
        // Lade verfügbare Status-Übergänge basierend auf dem aktuellen Instrumentenstatus
        $availableStatuses = $this->getAvailableStatusTransitions();

        // Hole alle Bewegungen für das Instrument der Defektmeldung ab dem Zeitpunkt der Bestellung
        $movements = collect();
        if ($this->order->defectReport?->instrument_id) {
            $movements = \App\Models\InstrumentMovement::where('instrument_id', $this->order->defectReport->instrument_id)
                ->where('performed_at', '>=', $this->order->created_at)
                ->where('movement_type', 'status_change')
                ->with(['performedBy', 'statusBeforeObject', 'statusAfterObject'])
                ->orderBy('performed_at', 'asc')
                ->get();
        }

        return view('livewire.purchase-orders.show-purchase-order', compact('manufacturers', 'availableStatuses', 'movements'));
    }
}
