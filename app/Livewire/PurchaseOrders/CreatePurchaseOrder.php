<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
use App\Models\DefectReport;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Neue Bestellung')]
class CreatePurchaseOrder extends Component
{
    #[Validate('required|exists:defect_reports,id')]
    public $defect_report_id = '';

    #[Validate('required|string|min:3|max:255')]
    public $supplier = '';

    #[Validate('nullable|numeric|min:0|max:999999.99')]
    public $estimated_cost = '';

    #[Validate('nullable|date')]
    public $estimated_delivery_date = '';

    #[Validate('nullable|string|max:1000')]
    public $notes = '';

    public $defectReports = [];

    public function mount()
    {
        // Lade nur Defektmeldungen, die noch keine Bestellung haben und für Bestellungen geeignet sind
        $this->defectReports = DefectReport::with(['instrument', 'reportedBy'])
            ->whereDoesntHave('purchaseOrder')
            ->whereIn('status', ['acknowledged', 'in_review']) // Nur bestätigte oder in Bearbeitung befindliche Defektmeldungen
            ->get();
    }

    public function save()
    {
        $this->validate();

        $purchaseOrder = PurchaseOrder::create([
            'defect_report_id' => $this->defect_report_id,
            'supplier' => $this->supplier,
            'estimated_cost' => $this->estimated_cost ?: null,
            'expected_delivery' => $this->estimated_delivery_date ?: null,
            'notes' => $this->notes,
            'status' => 'requested',
            'requested_by' => Auth::id(),
            'requested_at' => now(),
        ]);

        session()->flash('success', 'Bestellung wurde erfolgreich erstellt.');
        
        return redirect()->route('purchase-orders.index');
    }

    public function getSelectedDefectReportProperty()
    {
        if (!$this->defect_report_id) {
            return null;
        }

        return DefectReport::with(['instrument', 'reportedBy'])
            ->find($this->defect_report_id);
    }

    public function render()
    {
        return view('livewire.purchase-orders.create-purchase-order');
    }
}
