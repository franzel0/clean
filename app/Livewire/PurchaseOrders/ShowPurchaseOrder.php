<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
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
    public $supplier = '';
    public $expectedDelivery = '';

    public function mount(PurchaseOrder $order)
    {
        $this->order = $order->load([
            'defectReport.instrument',
            'defectReport.reportedBy',
            'defectReport.reportingDepartment',
            'requestedBy',
            'approvedBy',
            'receivedBy'
        ]);
        
        $this->supplier = $this->order->supplier;
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
        $this->validate([
            'supplier' => 'nullable|string|max:255',
            'actualCost' => 'nullable|numeric|min:0',
            'expectedDelivery' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $this->order->update([
            'supplier' => $this->supplier,
            'actual_cost' => $this->actualCost,
            'expected_delivery' => $this->expectedDelivery,
            'notes' => $this->notes,
        ]);

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

    public function render()
    {
        return view('livewire.purchase-orders.show-purchase-order');
    }
}
