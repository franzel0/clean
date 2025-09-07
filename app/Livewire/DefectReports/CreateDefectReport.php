<?php

namespace App\Livewire\DefectReports;

use App\Models\DefectReport;
use App\Models\Instrument;
use App\Models\OperatingRoom;
use App\Models\Department;
use App\Models\DefectType;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Neue Defektmeldung')]
class CreateDefectReport extends Component
{
    use WithFileUploads;

    public $instrument_id = '';
    public $operating_room_id = '';
    public $defect_type = '';
    public $defect_type_id = '';
    public $description = '';
    public $severity = 'mittel';
    public $photos = [];
    public $instrumentStatusId = '';

    protected $rules = [
        'instrument_id' => 'required|exists:instruments,id',
        'operating_room_id' => 'nullable|exists:operating_rooms,id',
        'defect_type' => 'nullable|string|max:255',
        'defect_type_id' => 'required|exists:defect_types,id',
        'description' => 'required|string|min:10',
        'severity' => 'required|in:niedrig,mittel,hoch,kritisch',
        'photos.*' => 'nullable|image|max:2048',
        'instrumentStatusId' => 'nullable|exists:instrument_statuses,id',
    ];

    public function mount($instrument = null)
    {
        // Get instrument from query parameter if not passed as route parameter
        $instrumentId = $instrument ?: request()->get('instrument');
        
        if ($instrumentId) {
            $this->instrument_id = $instrumentId;
        }
    }

    public function getAvailableInstrumentStatusesProperty()
    {
        return \App\Models\InstrumentStatus::availableInDefectReports()->get();
    }

    public function updated($propertyName)
    {
        
    }

    public function submit()
    {
        $this->validate();

        $photoUrls = [];
        if ($this->photos) {
            foreach ($this->photos as $photo) {
                $photoUrls[] = $photo->store('defect-photos', 'public');
            }
        }

        $report = DefectReport::create([
            'instrument_id' => $this->instrument_id,
            'reported_by' => Auth::user()->id,
            'reporting_department_id' => Auth::user()->department_id,
            'operating_room_id' => $this->operating_room_id ?: null,
            'defect_type' => $this->defect_type,
            'defect_type_id' => $this->defect_type_id,
            'description' => $this->description,
            'severity' => $this->severity,
            'reported_at' => now(),
            'photos' => $photoUrls,
        ]);

        // Update instrument status via MovementService to avoid double entries
        $instrument = Instrument::find($this->instrument_id);
        $oldStatusId = $instrument->status_id;
        
        // Use the selected status or default to 'Defekt gemeldet'
        $targetStatusId = $this->instrumentStatusId;
        if (!$targetStatusId) {
            $defectReportedStatus = \App\Models\InstrumentStatus::where('name', 'Defekt gemeldet')->first();
            $targetStatusId = $defectReportedStatus?->id;
        }
        
        if ($targetStatusId && $targetStatusId != $oldStatusId) {
            // Use MovementService which handles both movement logging AND status update
            \App\Services\MovementService::logMovement(
                instrument: $instrument,
                movementType: 'status_change',
                statusBefore: $oldStatusId,
                statusAfter: $targetStatusId,
                notes: 'Defekt gemeldet: ' . $this->defect_type . ' - ' . $report->report_number,
                movedBy: Auth::user()->id
            );
            
            // Explicit status update as fallback (MovementService should handle this, but just to be sure)
            $instrument->refresh();
            if ($instrument->status_id != $targetStatusId) {
                $instrument->update(['status_id' => $targetStatusId]);
            }
        }

        session()->flash('message', 'Defektmeldung erfolgreich erstellt: ' . $report->report_number);
        
        return redirect()->route('defect-reports.index');
    }

    public function render()
    {
        $instruments = Instrument::active()->get();
        $operating_rooms = OperatingRoom::active()->get();
        $defectTypes = DefectType::active()->ordered()->get();
        
        return view('livewire.defect-reports.create-defect-report', compact('instruments', 'operating_rooms', 'defectTypes'));
    }
}
