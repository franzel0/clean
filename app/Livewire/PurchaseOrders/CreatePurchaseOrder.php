<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
use App\Models\DefectReport;
use App\Models\Manufacturer;
use App\Models\PurchaseOrderStatus;
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

    #[Validate('required|exists:manufacturers,id')]
    public $manufacturer_id = '';

    #[Validate('nullable|numeric|min:0|max:999999.99')]
    public $estimated_cost = '';

    #[Validate('nullable|date')]
    public $estimated_delivery_date = '';

    #[Validate('nullable|string|max:1000')]
    public $notes = '';

    #[Validate('nullable|exists:purchase_order_statuses,id')]
    public $status_id = '';

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
        $this->validate([
            'defect_report_id' => 'required|exists:defect_reports,id',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'estimated_cost' => 'nullable|numeric|min:0|max:999999.99',
            'estimated_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'status_id' => 'nullable|exists:purchase_order_statuses,id',
        ], [
            'defect_report_id.required' => 'Bitte wählen Sie eine Defektmeldung aus.',
            'defect_report_id.exists' => 'Die ausgewählte Defektmeldung ist nicht gültig.',
            'manufacturer_id.required' => 'Bitte wählen Sie einen Hersteller aus.',
            'manufacturer_id.exists' => 'Der ausgewählte Hersteller ist nicht gültig.',
            'estimated_cost.numeric' => 'Die geschätzten Kosten müssen eine Zahl sein.',
            'estimated_cost.min' => 'Die geschätzten Kosten können nicht negativ sein.',
            'estimated_cost.max' => 'Die geschätzten Kosten sind zu hoch.',
            'estimated_delivery_date.date' => 'Bitte geben Sie ein gültiges Lieferdatum ein.',
            'notes.max' => 'Die Notizen dürfen maximal 1000 Zeichen lang sein.',
        ]);

        $purchaseOrder = PurchaseOrder::create([
            'defect_report_id' => $this->defect_report_id,
            'manufacturer_id' => $this->manufacturer_id,
            'estimated_cost' => $this->estimated_cost ?: null,
            'expected_delivery' => $this->estimated_delivery_date ?: null,
            'notes' => $this->notes,
            'status' => 'requested',
            'status_id' => $this->status_id,
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
        $manufacturers = Manufacturer::active()->ordered()->get();
        $purchaseOrderStatuses = PurchaseOrderStatus::active()->ordered()->get();
        
        return view('livewire.purchase-orders.create-purchase-order', compact('manufacturers', 'purchaseOrderStatuses'));
    }
}
