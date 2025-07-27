<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('components.layouts.app')]
#[Title('Bestellung anzeigen')]
class ShowPurchaseOrder extends Component
{
    public PurchaseOrder $order;

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
    }

    public function downloadPdf()
    {
        $pdf = Pdf::loadView('pdf.purchase-order', ['order' => $this->order]);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'bestellung-' . $this->order->order_number . '.pdf');
    }

    public function render()
    {
        return view('livewire.purchase-orders.show-purchase-order');
    }
}
