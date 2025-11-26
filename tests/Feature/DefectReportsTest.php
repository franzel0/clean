<?php

use App\Models\User;
use App\Models\DefectReport;
use App\Models\Instrument;
use App\Models\Department;
use App\Models\DefectType;
use App\Models\InstrumentStatus;
use App\Livewire\DefectReports\DefectReportsList;
use App\Livewire\DefectReports\EditDefectReport;
use Livewire\Livewire;

uses()->group('sequential');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    $this->department = Department::factory()->create();
    $this->defectType = DefectType::factory()->create();
    $this->instrument = Instrument::factory()->create();
    
    $this->defectStatus = InstrumentStatus::firstOrCreate(
        ['name' => 'Defekt gemeldet'],
        ['color' => '#ff0000', 'is_active' => true]
    );
});

it('can render defect reports list', function () {
    $reports = DefectReport::factory(3)->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
    ]);

    Livewire::test(DefectReportsList::class)
        ->assertSuccessful()
        ->assertSee($this->instrument->name);
});

it('can search defect reports', function () {
    $searchableInstrument = Instrument::factory()->create([
        'name' => 'Searchable Instrument',
        'serial_number' => 'SEARCH-123'
    ]);
    
    $searchableReport = DefectReport::factory()->create([
        'description' => 'Searchable defect description',
        'instrument_id' => $searchableInstrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
    ]);
    
    $otherReport = DefectReport::factory()->create([
        'description' => 'Other defect description',
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
    ]);

    Livewire::test(DefectReportsList::class)
        ->set('search', 'Searchable')
        ->assertSee('Searchable Instrument')
        ->assertDontSee($this->instrument->name);
});

it('can filter by completion status', function () {
    $openInstrument = Instrument::factory()->create(['name' => 'Open Report Instrument']);
    $completedInstrument = Instrument::factory()->create(['name' => 'Completed Report Instrument']);
    
    $openReport = DefectReport::factory()->create([
        'is_completed' => false,
        'instrument_id' => $openInstrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
    ]);
    
    $completedReport = DefectReport::factory()->create([
        'is_completed' => true,
        'instrument_id' => $completedInstrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
    ]);

    Livewire::test(DefectReportsList::class)
        ->set('completionFilter', 'active')
        ->assertSee('Open Report Instrument')
        ->assertDontSee('Completed Report Instrument');
});

// Test removed - acknowledged_at column doesn't exist
// it('can acknowledge a defect report', function () { ... });

it('can edit a defect report', function () {
    $availableStatus = InstrumentStatus::firstOrCreate(
        ['name' => 'Verfügbar'],
        ['color' => '#00ff00', 'bg_class' => 'bg-green-100', 'text_class' => 'text-green-800', 'is_active' => true]
    );
    
    $report = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
        'description' => 'Original description',
        'is_completed' => false,
    ]);

    Livewire::test(EditDefectReport::class, ['report' => $report])
        ->set('description', 'Updated description')
        ->set('is_completed', true)
        ->set('resolution_notes', 'Fixed the issue')
        ->set('instrument_status_id', $availableStatus->id)
        ->call('submit')
        ->assertRedirect();

    $report->refresh();
    expect($report->description)->toBe('Updated description')
        ->and($report->is_completed)->toBeTrue()
        ->and($report->resolution_notes)->toBe('Fixed the issue')
        ->and($report->instrument->status_id)->toBe($availableStatus->id);
});

it('validates required fields in edit form', function () {
    $report = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
    ]);

    Livewire::test(EditDefectReport::class, ['report' => $report])
        ->set('description', '') // Required field
        ->call('submit')
        ->assertHasErrors(['description']);
});

it('can create purchase order from defect report', function () {
    $report = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
    ]);

    Livewire::test(DefectReportsList::class)
        ->call('createPurchaseOrder', $report->id)
        ->assertSuccessful();

    expect(\App\Models\PurchaseOrder::where('defect_report_id', $report->id)->exists())->toBeTrue();
});
