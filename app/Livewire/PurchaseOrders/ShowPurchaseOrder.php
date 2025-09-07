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
    public $showStatusModal = false;
    public $showStatusDropdown = false;
    public $newStatus = '';
    public $notes = '';
    public $actualCost = '';
    public $manufacturer_id = '';
    public $expectedDelivery = '';

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
        $this->expectedDelivery = $this->order->expected_delivery?->format('Y-m-d');
    }

    public function toggleStatusDropdown()
    {
        $this->showStatusDropdown = !$this->showStatusDropdown;
    }

    public function openStatusModal($statusId)
    {
        Log::info('openStatusModal called with status ID: ' . $statusId);
        $this->newStatus = $statusId; // Speichere die Status-ID statt Code
        $this->showStatusModal = true;
        $this->showStatusDropdown = false;
    }

    public function closeModal()
    {
        $this->showStatusModal = false;
        $this->newStatus = '';
    }

    public function confirmStatusUpdate()
    {
        Log::info('confirmStatusUpdate called with newStatus: ' . $this->newStatus);
        $this->updateStatus();
    }

    public function updateStatus()
    {
        // Authorization: Nur berechtigt Benutzer können Status ändern
        $this->authorize('updateStatus', $this->order);
        
        Log::info('updateStatus called with newStatus ID: ' . $this->newStatus);
        
        if (empty($this->newStatus)) {
            session()->flash('error', 'Kein Status ausgewählt.');
            return;
        }

        // Finde den Instrumentenstatus anhand der ID
        $instrumentStatus = \App\Models\InstrumentStatus::find($this->newStatus);
        if (!$instrumentStatus) {
            session()->flash('error', 'Instrumentenstatus nicht gefunden.');
            return;
        }

        // Aktualisiere den Instrumentenstatus über das DefectReport
        if ($this->order->defectReport && $this->order->defectReport->instrument) {
            Log::info('Updating instrument status to: ' . $instrumentStatus->name . ' (ID: ' . $instrumentStatus->id . ')');
            
            $this->order->defectReport->instrument->update([
                'status_id' => $instrumentStatus->id
            ]);

            Log::info('Instrument status updated. New status ID: ' . $this->order->defectReport->instrument->fresh()->status_id);

            // Setze zusätzliche Felder basierend auf Status-Namen
            $updateData = [];
            switch ($instrumentStatus->name) {
                case 'Ersatz bestellt':
                    Log::info('Processing "Ersatz bestellt" status');
                    // Order date sollte bereits gesetzt sein, da die PurchaseOrder bereits existiert
                    // Keine zusätzlichen Updates nötig
                    break;
                case 'Ersatz geliefert':
                    Log::info('Processing "Ersatz geliefert" status');
                    $updateData['received_at'] = now();
                    $updateData['received_by'] = Auth::user()->id;
                    // Update defect report resolution
                    $this->order->defectReport->update([
                        'is_resolved' => true,
                        'resolved_at' => now(),
                        'resolved_by' => Auth::id(),
                        'resolution_notes' => 'Automatisch als gelöst markiert - Ersatzgerät geliefert'
                    ]);
                    break;
                default:
                    Log::info('Processing status: ' . $instrumentStatus->name);
                    break;
            }

            // Aktualisiere Purchase Order falls zusätzliche Daten gesetzt wurden
            if (!empty($updateData)) {
                // Temporär Observer deaktivieren, um Status-Überschreibung zu vermeiden
                \App\Models\PurchaseOrder::withoutEvents(function () use ($updateData) {
                    $this->order->update($updateData);
                });
            }

            $this->order->refresh();
            $this->order->load(['defectReport.instrument.instrumentStatus']);

            session()->flash('success', 'Instrumentenstatus erfolgreich auf "' . $instrumentStatus->name . '" geändert.');
        } else {
            session()->flash('error', 'Kein Instrument gefunden, um den Status zu ändern.');
        }

        $this->showStatusModal = false;
        $this->newStatus = '';
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
        ], [
            'manufacturer_id.exists' => 'Bitte wählen Sie einen gültigen Hersteller aus.',
            'actualCost.numeric' => 'Die tatsächlichen Kosten müssen eine Zahl sein.',
            'actualCost.min' => 'Die tatsächlichen Kosten können nicht negativ sein.',
            'actualCost.max' => 'Die tatsächlichen Kosten sind zu hoch (max. 999.999,99 €).',
            'expectedDelivery.date' => 'Bitte geben Sie ein gültiges Lieferdatum ein.',
            'expectedDelivery.after_or_equal' => 'Das Lieferdatum kann nicht in der Vergangenheit liegen.',
            'notes.max' => 'Notizen dürfen maximal 2000 Zeichen lang sein.',
        ]);

        $this->order->update([
            'manufacturer_id' => $this->manufacturer_id,
            'actual_cost' => $this->actualCost,
            'expected_delivery' => $this->expectedDelivery,
            'notes' => $this->notes,
        ]);

        // Reload the manufacturer relationship after update
        $this->order->load('manufacturer');

        session()->flash('message', 'Bestelldetails erfolgreich aktualisiert.');
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
        if (empty($statusId)) {
            return 'Unbekannt';
        }

        $status = \App\Models\InstrumentStatus::find($statusId);
        return $status ? $status->name : 'Unbekannt';
    }

    public function render()
    {
        $manufacturers = Manufacturer::active()->ordered()->get();
        
        // Lade verfügbare Status-Übergänge basierend auf dem aktuellen Instrumentenstatus
        $availableStatuses = $this->getAvailableStatusTransitions();

        return view('livewire.purchase-orders.show-purchase-order', compact('manufacturers', 'availableStatuses'));
    }
}
