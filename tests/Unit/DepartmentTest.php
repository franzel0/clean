<?php

use App\Models\Department;
use App\Models\DefectReport;
use App\Models\Instrument;

it('can create a department', function () {
    $department = Department::factory()->create([
        'name' => 'OP Saal 1',
        'code' => 'OP1',
    ]);

    expect($department)->toBeInstanceOf(Department::class)
        ->and($department->name)->toBe('OP Saal 1')
        ->and($department->code)->toBe('OP1');
});

it('has defect reports relationship', function () {
    $department = Department::factory()->create();
    $defectReport = DefectReport::factory()->create([
        'reporting_department_id' => $department->id,
    ]);

    expect($department->defectReports)->toHaveCount(1)
        ->and($department->defectReports->first())->toBeInstanceOf(DefectReport::class);
});

it('has unique department codes', function () {
    Department::factory()->create(['code' => 'DEPT-123']);
    
    expect(fn() => Department::factory()->create(['code' => 'DEPT-123']))
        ->toThrow(\Exception::class);
});

it('stores department description', function () {
    $department = Department::factory()->create([
        'description' => 'Hauptoperationssaal für Herzchirurgie',
    ]);

    expect($department->description)->toBe('Hauptoperationssaal für Herzchirurgie');
});
