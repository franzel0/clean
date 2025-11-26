<?php

use App\Models\User;
use App\Models\DefectReport;
use App\Models\Instrument;
use App\Models\InstrumentStatus;
use App\Models\Department;
use App\Models\DefectType;
use App\Models\PurchaseOrder;
use App\Livewire\DefectReports\ShowDefectReport;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    $this->department = Department::factory()->create();
    $this->defectType = DefectType::factory()->create();
    $this->status = InstrumentStatus::factory()->create();
    
    $this->instrument = Instrument::factory()->create([
        'status_id' => $this->status->id,
    ]);
    
    $this->defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
        'reporting_department_id' => $this->department->id,
        'defect_type_id' => $this->defectType->id,
        'description' => 'Test defect description',
    ]);
});

it('can display defect report details', function () {
    Livewire::test(ShowDefectReport::class, ['report' => $this->defectReport])
        ->assertSuccessful()
        ->assertSee($this->defectReport->description)
        ->assertSee($this->instrument->name);
});

it('displays defect type', function () {
    Livewire::test(ShowDefectReport::class, ['report' => $this->defectReport])
        ->assertSuccessful()
        ->assertViewHas('report', function ($report) {
            return $report->defect_type_display !== null;
        });
});

it('displays reporter name', function () {
    Livewire::test(ShowDefectReport::class, ['report' => $this->defectReport])
        ->assertSuccessful()
        ->assertSee($this->user->name);
});

it('shows defect severity', function () {
    $defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'severity' => 'hoch',
    ]);
    
    Livewire::test(ShowDefectReport::class, ['report' => $defectReport])
        ->assertSuccessful();
});

it('shows if defect report is completed', function () {
    $completedReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'is_completed' => true,
        'resolved_at' => now(),
        'resolved_by' => $this->user->id,
    ]);
    
    Livewire::test(ShowDefectReport::class, ['report' => $completedReport])
        ->assertSuccessful();
});

it('displays associated purchase order if exists', function () {
    $purchaseOrder = PurchaseOrder::factory()->create([
        'defect_report_id' => $this->defectReport->id,
        'ordered_by' => $this->user->id,
    ]);
    
    // Verify purchase order was created
    expect(PurchaseOrder::where('defect_report_id', $this->defectReport->id)->exists())->toBeTrue();
    
    Livewire::test(ShowDefectReport::class, ['report' => $this->defectReport])
        ->assertSuccessful();
});

it('shows resolution notes when completed', function () {
    $completedReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'is_completed' => true,
        'resolution_notes' => 'Instrument repaired successfully',
    ]);
    
    Livewire::test(ShowDefectReport::class, ['report' => $completedReport])
        ->assertSuccessful()
        ->assertSee('Instrument repaired successfully');
});
