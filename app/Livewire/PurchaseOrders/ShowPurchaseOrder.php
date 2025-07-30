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
            'defectReport.instrument',
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

    public function openStatusModal($status)
    {
        Log::info('openStatusModal called with status: ' . $status);
        $this->newStatus = $status;
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
        
        Log::info('updateStatus called with newStatus: ' . $this->newStatus);
        
        if (empty($this->newStatus)) {
            session()->flash('error', 'Kein Status ausgewählt.');
            return;
        }

        $updateData = [
            'status' => $this->newStatus
        ];

        switch ($this->newStatus) {
            case 'approved':
                $updateData['approved_at'] = now();
                $updateData['approved_by'] = Auth::user()->id;
                break;
            case 'ordered':
                $updateData['ordered_at'] = now();
                break;
            case 'shipped':
                // Shipped status - no additional fields needed
                break;
            case 'received':
                $updateData['received_at'] = now();
                $updateData['received_by'] = Auth::user()->id;
                // Update defect report status
                if ($this->order->defectReport) {
                    $this->order->defectReport->update(['status' => 'received']);
                }
                break;
            case 'completed':
                if ($this->order->defectReport) {
                    $this->order->defectReport->update(['status' => 'closed']);
                }
                break;
        }

        $this->order->update($updateData);
        $this->order->refresh();
        $this->closeModal();
        
        session()->flash('message', 'Status erfolgreich von "' . $this->order->getOriginal('status') . '" zu "' . $this->newStatus . '" geändert.');
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
        $transitions = [];
        
        switch ($this->order->status) {
            case 'requested':
                $transitions = [
                    'approved' => 'Genehmigen',
                    'ordered' => 'Direkt als bestellt markieren'
                ];
                break;
                
            case 'approved':
                $transitions = [
                    'ordered' => 'Als bestellt markieren'
                ];
                break;
                
            case 'ordered':
                $transitions = [
                    'shipped' => 'Als versandt markieren',
                    'received' => 'Direkt als erhalten markieren'
                ];
                break;
                
            case 'shipped':
                $transitions = [
                    'received' => 'Als erhalten markieren'
                ];
                break;
                
            case 'received':
                $transitions = [
                    'completed' => 'Abschließen'
                ];
                break;
        }
        
        // Stornieren ist immer möglich (außer bei already cancelled/completed)
        if (!in_array($this->order->status, ['cancelled', 'completed'])) {
            $transitions['cancelled'] = 'Stornieren';
        }
        
        return $transitions;
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

    public function render()
    {
        $manufacturers = Manufacturer::active()->ordered()->get();

        return view('livewire.purchase-orders.show-purchase-order', compact('manufacturers'));
    }
}
