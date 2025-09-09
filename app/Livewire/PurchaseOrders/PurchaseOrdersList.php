<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Bestellungen')]
class PurchaseOrdersList extends Component
{
    use WithPagination;

    public $search = '';
    public $departmentFilter = '';
    public $completionFilter = 'active';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }

    public function updatingCompletionFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->departmentFilter = '';
        $this->completionFilter = 'active';
        $this->resetPage();
    }

    public function markAsReceived($orderId)
    {
        $order = PurchaseOrder::findOrFail($orderId);
        
        // Update the received information
        $order->update([
            'received_at' => now(),
            'received_by' => Auth::user()->id,
        ]);

        // Update defect report and instrument status to "Geliefert"
        if ($order->defectReport && $order->defectReport->instrument) {
            $receivedStatus = \App\Models\InstrumentStatus::where('name', 'Geliefert')->first();
            if ($receivedStatus) {
                $order->defectReport->instrument->update([
                    'status_id' => $receivedStatus->id
                ]);
            }
        }

        session()->flash('message', 'Bestellung als erhalten markiert.');
    }

    public function downloadPdf($orderId)
    {
        $order = PurchaseOrder::with([
            'defectReport.instrument',
            'defectReport.reportedBy',
            'defectReport.reportingDepartment',
            'requestedBy',
            'approvedBy',
            'receivedBy'
        ])->findOrFail($orderId);

        $pdf = Pdf::loadView('pdf.purchase-order', ['order' => $order]);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'bestellung-' . $order->order_number . '.pdf');
    }

    public function render()
    {
        $query = PurchaseOrder::with([
            'defectReport.instrument.instrumentStatus',
            'defectReport.reportingDepartment',
            'requestedBy',
            'receivedBy',
            'manufacturer'
        ])
        ->when($this->search, function ($query) {
            $query->whereHas('defectReport.instrument', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $this->search . '%');
            })
            ->orWhere('order_number', 'like', '%' . $this->search . '%');
        })
        ->when($this->departmentFilter, function ($query) {
            $query->whereHas('defectReport', function ($q) {
                $q->where('reporting_department_id', $this->departmentFilter);
            });
        })
        ->when($this->completionFilter, function ($query) {
            if ($this->completionFilter === 'active') {
                $query->where('is_completed', false);
            } elseif ($this->completionFilter === 'completed') {
                $query->where('is_completed', true);
            }
        });

        $orders = $query->latest()->paginate(15);

        $departments = Department::active()->get();

        return view('livewire.purchase-orders.purchase-orders-list', compact(
            'orders',
            'departments'
        ));
    }
}
