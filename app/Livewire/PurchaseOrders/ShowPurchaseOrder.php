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
    public $actualCost = '';
    public $manufacturer_id = '';
    public $expectedDelivery = '';
    public $instrumentStatusId = '';

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
        $this->actualCost = $this->order->actual_cost;
        $this->notes = $this->order->notes;
        $this->expectedDelivery = $this->order->expected_delivery;
        $this->instrumentStatusId = $this->order->defectReport?->instrument?->status_id ?? '';
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
            'actualCost' => 'nullable|numeric|min:0|max:999999.99', // Max-Limit für Sicherheit
            'expectedDelivery' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:2000', // Limit für Notes
            'instrumentStatusId' => 'nullable|exists:instrument_statuses,id',
        ], [
            'manufacturer_id.exists' => 'Bitte wählen Sie einen gültigen Hersteller aus.',
            'actualCost.numeric' => 'Die tatsächlichen Kosten müssen eine Zahl sein.',
            'actualCost.min' => 'Die tatsächlichen Kosten können nicht negativ sein.',
            'actualCost.max' => 'Die tatsächlichen Kosten sind zu hoch (max. 999.999,99 €).',
            'expectedDelivery.date' => 'Bitte geben Sie ein gültiges Lieferdatum ein.',
            'expectedDelivery.after_or_equal' => 'Das Lieferdatum kann nicht in der Vergangenheit liegen.',
            'notes.max' => 'Notizen dürfen maximal 2000 Zeichen lang sein.',
            'instrumentStatusId.exists' => 'Bitte wählen Sie einen gültigen Instrumentenstatus aus.',
        ]);

        $this->order->update([
            'manufacturer_id' => $this->manufacturer_id,
            'actual_cost' => $this->actualCost,
            'expected_delivery' => $this->expectedDelivery,
            'notes' => $this->notes,
        ]);

        // Instrumentenstatus aktualisieren falls vorhanden
        if ($this->instrumentStatusId && $this->order->defectReport?->instrument) {
            $this->order->defectReport->instrument->update([
                'status_id' => $this->instrumentStatusId
            ]);
        }

        // Reload the manufacturer relationship after update
        $this->order->load(['manufacturer', 'defectReport.instrument.instrumentStatus']);

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

        return view('livewire.purchase-orders.show-purchase-order', compact('manufacturers', 'availableStatuses'));
    }
}
