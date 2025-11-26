<?php

use App\Models\Instrument;
use App\Models\InstrumentStatus;
use App\Models\DefectReport;
use App\Models\User;
use App\Services\InstrumentStatusService;

uses()->group('sequential');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->service = new InstrumentStatusService();
    
    $this->availableStatus = InstrumentStatus::firstOrCreate(
        ['name' => 'Verfügbar'],
        ['color' => '#00ff00', 'is_active' => true]
    );
    
    $this->defectReportedStatus = InstrumentStatus::firstOrCreate(
        ['name' => 'Defekt gemeldet'],
        ['color' => '#ff0000', 'is_active' => true]
    );
    
    $this->instrument = Instrument::factory()->create([
        'status_id' => $this->availableStatus->id
    ]);
});

it('updates instrument status when defect report is created', function () {
    $defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'is_completed' => false,
    ]);

    $this->service->updateStatusOnDefectReport($defectReport, 'created');

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
    $this->service->updateStatusOnDefectReport($defectReport, 'resolved');

    // Service should set to 'Repariert' when no pending purchase order
    $repairedStatus = InstrumentStatus::where('name', 'Repariert')->first();
    expect($this->instrument->fresh()->status_id)->toBe($repairedStatus->id);
});

it('handles multiple defect reports correctly', function () {
    // Create first defect report
    $defectReport1 = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'is_completed' => false,
    ]);
    
    $this->service->updateStatusOnDefectReport($defectReport1, 'created');
    expect($this->instrument->fresh()->status_id)->toBe($this->defectReportedStatus->id);
    
    // Create second defect report
    $defectReport2 = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'is_completed' => false,
    ]);
    
    // Resolve first report - service sets to "Repariert" (no purchase order logic for multiple reports)
    $defectReport1->update(['is_completed' => true]);
    $this->service->updateStatusOnDefectReport($defectReport1, 'resolved');
    
    $repairedStatus = InstrumentStatus::where('name', 'Repariert')->first();
    expect($this->instrument->fresh()->status_id)->toBe($repairedStatus->id);
    
    // Resolve second report - also sets to "Repariert"
    $defectReport2->update(['is_completed' => true]);
    $this->service->updateStatusOnDefectReport($defectReport2, 'resolved');
    expect($this->instrument->fresh()->status_id)->toBe($repairedStatus->id);
});

it('can get available status transitions for an instrument', function () {
    $this->instrument->instrumentStatus()->associate($this->availableStatus);
    $this->instrument->save();
    
    $transitions = $this->service->getAvailableStatusTransitions($this->instrument);
    
    expect($transitions)->toBeArray();
});

it('validates status transitions', function () {
    $this->instrument->instrumentStatus()->associate($this->availableStatus);
    $this->instrument->save();
    
    $canTransition = $this->service->canTransitionTo($this->instrument, 'In Wartung');
    
    expect($canTransition)->toBeTrue();
});

it('handles status not found gracefully', function () {
    // Should not throw an exception when status doesn't exist
    $this->service->setInstrumentStatus($this->instrument, 'Non Existent Status');
    
    // Instrument status should remain unchanged
    expect($this->instrument->fresh()->status_id)->toBe($this->availableStatus->id);
});
