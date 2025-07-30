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
    public $statusFilter = '';
    public $departmentFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->departmentFilter = '';
        $this->resetPage();
    }

    public function markAsReceived($orderId)
    {
        $order = PurchaseOrder::findOrFail($orderId);
        $order->update([
            'status' => 'received',
            'received_at' => now(),
            'received_by' => Auth::user()->id,
        ]);

        // Update defect report status
        if ($order->defectReport) {
            $order->defectReport->update(['status' => 'received']);
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
            'defectReport.instrument',
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
        ->when($this->statusFilter, function ($query) {
            $query->where('status', $this->statusFilter);
        })
        ->when($this->departmentFilter, function ($query) {
            $query->whereHas('defectReport', function ($q) {
                $q->where('reporting_department_id', $this->departmentFilter);
            });
        });

        $orders = $query->latest()->paginate(15);

        $statuses = ['requested', 'approved', 'ordered', 'shipped', 'received', 'completed', 'cancelled'];
        $departments = Department::active()->get();

        return view('livewire.purchase-orders.purchase-orders-list', compact(
            'orders',
            'statuses',
            'departments'
        ));
    }
}
