<?php

use App\Models\DefectReport;
use App\Models\Instrument;
use App\Models\User;
use App\Models\Department;
use App\Models\DefectType;
use App\Models\InstrumentStatus;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->department = Department::factory()->create();
    $this->defectType = DefectType::factory()->create();
    $this->defectStatus = InstrumentStatus::factory()->create([
        'bg_class' => 'bg-orange-100',
        'text_class' => 'text-orange-800'
    ]);
    $this->instrument = Instrument::factory()->create([
        'status_id' => $this->defectStatus->id,
    ]);
});

it('can create a defect report', function () {
    $report = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
        'description' => 'Test defect description',
        'severity' => 'hoch',
        'is_completed' => false,
    ]);

    expect($report->instrument_id)->toBe($this->instrument->id)
        ->and($report->reported_by)->toBe($this->user->id)
        ->and($report->reporting_department_id)->toBe($this->department->id)
        ->and($report->description)->toBe('Test defect description')
        ->and($report->severity)->toBe('hoch')
        ->and($report->is_completed)->toBeFalse();
});

it('has correct relationships', function () {
    $report = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
    ]);

    expect($report->instrument)->toBeInstanceOf(Instrument::class)
        ->and($report->reportedBy)->toBeInstanceOf(User::class)
        ->and($report->reportingDepartment)->toBeInstanceOf(Department::class)
        ->and($report->defectType)->toBeInstanceOf(DefectType::class);
});

it('generates correct status display based on completion', function () {
    $openReport = DefectReport::factory()->create(['is_completed' => false]);
    $completedReport = DefectReport::factory()->create(['is_completed' => true]);

    expect($openReport->status_display)->toBe('Offen')
        ->and($completedReport->status_display)->toBe('Abgeschlossen');
});

it('generates correct status badge classes', function () {
    $openReport = DefectReport::factory()->create(['is_completed' => false]);
    $completedReport = DefectReport::factory()->create(['is_completed' => true]);

    expect($openReport->status_badge_class)->toBe('bg-red-100 text-red-800')
        ->and($completedReport->status_badge_class)->toBe('bg-green-100 text-green-800');
});

it('can be completed with notes', function () {
    $report = DefectReport::factory()->create(['is_completed' => false]);
    
    $report->update([
        'is_completed' => true,
        'resolved_at' => now(),
        'resolved_by' => $this->user->id,
        'resolution_notes' => 'Fixed by replacing damaged part'
    ]);

    expect($report->fresh()->is_completed)->toBeTrue()
        ->and($report->fresh()->resolved_at)->not->toBeNull()
        ->and($report->fresh()->resolved_by)->toBe($this->user->id)
        ->and($report->fresh()->resolution_notes)->toBe('Fixed by replacing damaged part');
});

it('validates severity levels', function () {
    $validSeverities = ['niedrig', 'mittel', 'hoch', 'kritisch'];
    
    foreach ($validSeverities as $severity) {
        $report = DefectReport::factory()->create(['severity' => $severity]);
        expect($report->severity)->toBe($severity);
    }
});

it('generates severity display correctly', function () {
    $report = DefectReport::factory()->create(['severity' => 'kritisch']);
    
    expect($report->severity_display)->toBe('Kritisch');
});
