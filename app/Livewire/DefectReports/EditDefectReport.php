<?php

namespace App\Livewire\DefectReports;

use App\Models\DefectReport;
use App\Models\Instrument;
use App\Models\OperatingRoom;
use App\Models\DefectType;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Defektmeldung bearbeiten')]
class EditDefectReport extends Component
{
    use WithFileUploads;

    public DefectReport $report;

    public $instrument_id = '';
    public $operating_room_id = '';
    public $defect_type = '';
    public $defect_type_id = '';
    public $description = '';
    public $severity = 'mittel';
    public $is_completed = false;
    public $resolution_notes = '';
    public $photos = [];
    public $existing_photos = [];

    // Instrument Status Properties
    public $instrument_status_id = '';
    public $instruments = [];
    public $instrumentStatuses = [];

    protected $rules = [
        'instrument_id' => 'required|exists:instruments,id',
        'operating_room_id' => 'nullable|exists:operating_rooms,id',
        'defect_type' => 'nullable|string|max:255',
        'defect_type_id' => 'required|exists:defect_types,id',
        'description' => 'required|string|min:10',
        'severity' => 'required|in:niedrig,mittel,hoch,kritisch',
        'is_completed' => 'boolean',
        'resolution_notes' => 'nullable|string',
        'photos.*' => 'nullable|image|max:2048',
        'instrument_status_id' => 'required|exists:instrument_statuses,id',
    ];

    public function mount(DefectReport $report)
    {
        $this->report = $report;
        $this->instrument_id = $report->instrument_id;
        $this->operating_room_id = $report->operating_room_id;
        $this->defect_type = $report->defect_type;
        $this->defect_type_id = $report->defect_type_id;
        $this->description = $report->description;
        $this->severity = $report->severity;
        $this->is_completed = $report->is_completed ?? false;
        $this->resolution_notes = $report->resolution_notes ?? '';
        $this->existing_photos = $report->photos ?? [];
        
        // Load instrument status
        $this->instrument_status_id = $report->instrument->status_id ?? '';
        
        // Load data for dropdowns
        $this->instruments = \App\Models\Instrument::with('instrumentStatus')->get();
        $this->instrumentStatuses = \App\Models\InstrumentStatus::all();
    }

    public function removeExistingPhoto($index)
    {
        unset($this->existing_photos[$index]);
        $this->existing_photos = array_values($this->existing_photos);
    }

    public function submit()
    {
        $this->validate();

        $photoUrls = $this->existing_photos;
        
        if ($this->photos) {
            foreach ($this->photos as $photo) {
                $photoUrls[] = $photo->store('defect-photos', 'public');
            }
        }

        $this->report->update([
            'instrument_id' => $this->instrument_id,
            'operating_room_id' => $this->operating_room_id ?: null,
            'defect_type' => $this->defect_type,
            'defect_type_id' => $this->defect_type_id,
            'description' => $this->description,
            'severity' => $this->severity,
            'is_completed' => $this->is_completed,
            'resolution_notes' => $this->resolution_notes,
            'resolved_at' => $this->is_completed ? now() : null,
            'resolved_by' => $this->is_completed ? Auth::id() : null,
            'photos' => $photoUrls,
        ]);

        // Update instrument status directly if changed
        if ($this->instrument_status_id && $this->instrument_status_id != $this->report->instrument->status_id) {
            $oldStatusId = $this->report->instrument->status_id;
            
            // Update instrument status directly
            $this->report->instrument->update(['status_id' => $this->instrument_status_id]);
            
            // Log movement without updating instrument again
            \App\Services\MovementService::logMovementOnly(
                instrument: $this->report->instrument,
                movementType: 'status_change',
                statusBefore: $oldStatusId,
                statusAfter: $this->instrument_status_id,
                notes: 'Status aktualisiert via Defektmeldung: ' . $this->report->report_number,
                movedBy: Auth::user()->id
            );
        }
        
        session()->flash('message', 'Defektmeldung wurde erfolgreich aktualisiert.');
        
        return redirect()->route('defect-reports.show', $this->report);
    }

    public function render()
    {
        $instruments = Instrument::all(); // Alle Instrumente für Bearbeitung
        $operating_rooms = OperatingRoom::active()->get();
        $defectTypes = DefectType::active()->ordered()->get();
        
        // Stelle sicher, dass instrumentStatuses verfügbar sind
        if (empty($this->instrumentStatuses)) {
            $this->instrumentStatuses = \App\Models\InstrumentStatus::all();
        }
        
        return view('livewire.defect-reports.edit-defect-report', compact('instruments', 'operating_rooms', 'defectTypes'));
    }
}
