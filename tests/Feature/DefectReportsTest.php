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

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    $this->department = Department::factory()->create();
    $this->defectType = DefectType::factory()->create();
    $this->instrument = Instrument::factory()->create();
    $this->defectStatus = InstrumentStatus::factory()->create([
        'name' => 'Defekt gemeldet',
        'bg_class' => 'bg-orange-100',
        'text_class' => 'text-orange-800'
    ]);
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
        ->assertSee($reports->first()->description);
});

it('can search defect reports', function () {
    $searchableReport = DefectReport::factory()->create([
        'description' => 'Searchable defect description',
        'instrument_id' => $this->instrument->id,
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
        ->assertSee('Searchable defect description')
        ->assertDontSee('Other defect description');
});

it('can filter by resolution status', function () {
    $openReport = DefectReport::factory()->create([
        'is_resolved' => false,
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
    ]);
    
    $resolvedReport = DefectReport::factory()->create([
        'is_resolved' => true,
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
    ]);

    Livewire::test(DefectReportsList::class)
        ->set('statusFilter', 'open')
        ->assertSee($openReport->description)
        ->assertDontSee($resolvedReport->description);
});

it('can acknowledge a defect report', function () {
    $report = DefectReport::factory()->create([
        'acknowledged_at' => null,
        'acknowledged_by' => null,
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
    ]);

    Livewire::test(DefectReportsList::class)
        ->call('acknowledgeReport', $report->id)
        ->assertSuccessful();

    $report->refresh();
    expect($report->acknowledged_at)->not->toBeNull()
        ->and($report->acknowledged_by)->toBe($this->user->id);
});

it('can edit a defect report', function () {
    $availableStatus = InstrumentStatus::factory()->create([
        'name' => 'Verfügbar',
        'bg_class' => 'bg-green-100',
        'text_class' => 'text-green-800'
    ]);
    
    $report = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
        'description' => 'Original description',
        'is_resolved' => false,
    ]);

    Livewire::test(EditDefectReport::class, ['report' => $report])
        ->set('description', 'Updated description')
        ->set('is_resolved', true)
        ->set('resolution_notes', 'Fixed the issue')
        ->set('instrument_status_id', $availableStatus->id)
        ->call('submit')
        ->assertRedirect();

    $report->refresh();
    expect($report->description)->toBe('Updated description')
        ->and($report->is_resolved)->toBeTrue()
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
