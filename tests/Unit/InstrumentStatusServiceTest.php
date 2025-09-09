<?php

use App\Models\Instrument;
use App\Models\InstrumentStatus;
use App\Models\DefectReport;
use App\Models\User;
use App\Services\InstrumentStatusService;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->service = new InstrumentStatusService();
    
    $this->availableStatus = InstrumentStatus::factory()->create([
        'name' => 'Verfügbar',
        'bg_class' => 'bg-green-100',
        'text_class' => 'text-green-800'
    ]);
    
    $this->defectReportedStatus = InstrumentStatus::factory()->create([
        'name' => 'Defekt gemeldet',
        'bg_class' => 'bg-orange-100',
        'text_class' => 'text-orange-800'
    ]);
    
    $this->instrument = Instrument::factory()->create([
        'status_id' => $this->availableStatus->id
    ]);
});

it('updates instrument status when defect report is created', function () {
    $defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'is_completed' => false,
    ]);

    $this->service->updateStatusOnDefectReport($defectReport);

    expect($this->instrument->fresh()->status_id)->toBe($this->defectReportedStatus->id);
});

it('updates instrument status when defect report is resolved', function () {
    // First create a defect and set status
    $defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'is_completed' => false,
    ]);
    
    $this->instrument->update(['status_id' => $this->defectReportedStatus->id]);
    
    // Now resolve the defect
    $defectReport->update(['is_completed' => true]);
    $this->service->updateStatusOnDefectReport($defectReport);

    expect($this->instrument->fresh()->status_id)->toBe($this->availableStatus->id);
});

it('handles multiple defect reports correctly', function () {
    // Create first defect report
    $defectReport1 = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'is_completed' => false,
    ]);
    
    $this->service->updateStatusOnDefectReport($defectReport1);
    expect($this->instrument->fresh()->status_id)->toBe($this->defectReportedStatus->id);
    
    // Create second defect report
    $defectReport2 = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'is_completed' => false,
    ]);
    
    // Resolve first report - status should still be "Defekt gemeldet" because second is open
    $defectReport1->update(['is_completed' => true]);
    $this->service->updateStatusOnDefectReport($defectReport1);
    expect($this->instrument->fresh()->status_id)->toBe($this->defectReportedStatus->id);
    
    // Resolve second report - now status should be "Verfügbar"
    $defectReport2->update(['is_completed' => true]);
    $this->service->updateStatusOnDefectReport($defectReport2);
    expect($this->instrument->fresh()->status_id)->toBe($this->availableStatus->id);
});

it('can get available status types', function () {
    $statusTypes = $this->service->getAvailableStatusTypes();
    
    expect($statusTypes)->toBeArray()
        ->and($statusTypes)->toContain('Verfügbar')
        ->and($statusTypes)->toContain('Defekt gemeldet');
});

it('validates status transitions', function () {
    $canTransition = $this->service->canTransitionTo($this->availableStatus, $this->defectReportedStatus);
    
    expect($canTransition)->toBeTrue();
});

it('handles status not found gracefully', function () {
    $nonExistentStatus = InstrumentStatus::factory()->make(['name' => 'Non Existent']);
    
    expect(function () use ($nonExistentStatus) {
        $this->service->updateInstrumentStatus($this->instrument, $nonExistentStatus->name);
    })->not->toThrow();
});
