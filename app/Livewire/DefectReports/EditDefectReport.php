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
    public $status = '';
    public $photos = [];
    public $existing_photos = [];

    protected $rules = [
        'instrument_id' => 'required|exists:instruments,id',
        'operating_room_id' => 'nullable|exists:operating_rooms,id',
        'defect_type' => 'nullable|string|max:255',
        'defect_type_id' => 'required|exists:defect_types,id',
        'description' => 'required|string|min:10',
        'severity' => 'required|in:niedrig,mittel,hoch,kritisch',
        'status' => 'required|in:offen,in_bearbeitung,abgeschlossen,abgelehnt',
        'photos.*' => 'nullable|image|max:2048',
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
        $this->status = $report->status;
        $this->existing_photos = $report->photos ?? [];
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
            'status' => $this->status,
            'photos' => $photoUrls,
        ]);

        // Update instrument status based on defect report status
        if ($this->status === 'closed' || $this->status === 'repaired') {
            $activeStatus = \App\Models\InstrumentStatus::where('name', 'Verfügbar')->first();
            if ($activeStatus) {
                $this->report->instrument->update(['status_id' => $activeStatus->id]);
            }
        } else {
            $defectiveStatus = \App\Models\InstrumentStatus::where('name', 'Außer Betrieb')->first();
            if ($defectiveStatus) {
                $this->report->instrument->update(['status_id' => $defectiveStatus->id]);
            }
        }

        session()->flash('message', 'Defektmeldung wurde erfolgreich aktualisiert.');
        
        return redirect()->route('defect-reports.show', $this->report);
    }

    public function render()
    {
        $instruments = Instrument::all(); // Alle Instrumente für Bearbeitung
        $operating_rooms = OperatingRoom::active()->get();
        $defectTypes = DefectType::active()->ordered()->get();
        
        return view('livewire.defect-reports.edit-defect-report', compact('instruments', 'operating_rooms', 'defectTypes'));
    }
}
