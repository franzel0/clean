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

    #[Validate('nullable|exists:instrument_statuses,id')]
    public $status_id = '';

    public $defectReports = [];
    public $instrumentStatuses = [];

    public function mount(): void
    {
        // Lade Defektmeldungen, deren Instrumente den Status "Defekt bestätigt" haben
        $confirmedStatusId = \App\Models\InstrumentStatus::where('name', 'Defekt bestätigt')->first()?->id;
        
        $this->defectReports = DefectReport::with(['instrument', 'defectType', 'reportingDepartment', 'reportedBy'])
            ->whereHas('instrument', function ($query) use ($confirmedStatusId) {
                $query->where('status_id', $confirmedStatusId);
            })
            ->whereDoesntHave('purchaseOrder') // Singular! Keine bereits verknüpfte Bestellung
            ->orderBy('reported_at', 'desc')
            ->get();
            
        // Lade relevante Instrument-Status für das Dropdown
        $this->instrumentStatuses = \App\Models\InstrumentStatus::whereIn('name', [
            'Ersatz bestellt',
            'Ersatz geliefert', 
            'In Reparatur'
        ])->orderBy('sort_order')->get();
    }

    public function save()
    {
        $this->validate([
            'defect_report_id' => 'required|exists:defect_reports,id',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'estimated_cost' => 'nullable|numeric|min:0|max:999999.99',
            'estimated_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'status_id' => 'nullable|exists:instrument_statuses,id',
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
            'total_amount' => $this->estimated_cost ?: null,
            'expected_delivery' => $this->estimated_delivery_date ?: null,
            'notes' => $this->notes,
            'ordered_by' => Auth::id(),
            'order_date' => now(),
        ]);

        // Aktualisiere das Instrument Status auf "Ersatz bestellt" (wenn Status ausgewählt wurde)
        if (!empty($this->status_id)) {
            $defectReport = DefectReport::find($this->defect_report_id);
            if ($defectReport && $defectReport->instrument) {
                $defectReport->instrument->update(['status_id' => $this->status_id]);
            }
        }

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
        
        return view('livewire.purchase-orders.create-purchase-order', compact('manufacturers'));
    }
}
