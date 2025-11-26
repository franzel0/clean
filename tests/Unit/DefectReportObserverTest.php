<?php

use App\Models\DefectReport;
use App\Models\Instrument;
use App\Models\InstrumentStatus;
use App\Models\User;
use App\Services\InstrumentStatusService;

uses()->group('sequential');

beforeEach(function () {
    $this->user = User::factory()->create();
    
    // Use firstOrCreate for statuses to avoid UNIQUE constraint violations
    $this->availableStatus = InstrumentStatus::firstOrCreate(
        ['name' => 'Verfügbar'],
        ['color' => '#00ff00', 'is_active' => true]
    );
    $this->reportedStatus = InstrumentStatus::firstOrCreate(
        ['name' => 'Defekt gemeldet'],
        ['color' => '#ff0000', 'is_active' => true]
    );
    $this->confirmedStatus = InstrumentStatus::firstOrCreate(
        ['name' => 'Defekt bestätigt'],
        ['color' => '#ff9900', 'is_active' => true]
    );
    $this->repairedStatus = InstrumentStatus::firstOrCreate(
        ['name' => 'Repariert'],
        ['color' => '#00ff00', 'is_active' => true]
    );
    
    $this->instrument = Instrument::factory()->create([
        'status_id' => $this->availableStatus->id,
    ]);
});

it('updates instrument status when defect report is created', function () {
    $defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
    ]);

    expect($this->instrument->fresh()->status_id)->toBe($this->reportedStatus->id);
});

it('updates instrument status when defect report is completed', function () {
    $defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'is_completed' => false,
    ]);
    
    $this->instrument->update(['status_id' => $this->reportedStatus->id]);
    
    $defectReport->update([
        'is_completed' => true,
        'resolved_at' => now(),
        'resolved_by' => $this->user->id,
    ]);

    // Status should be updated when defect is resolved
    expect($this->instrument->fresh()->status_id)->not->toBe($this->reportedStatus->id);
});

it('does not change status if defect report is not completed', function () {
    DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'is_completed' => false,
    ]);

    expect($this->instrument->fresh()->status_id)->toBe($this->reportedStatus->id);
});
