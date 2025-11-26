<?php

use App\Models\DefectType;
use App\Models\DefectReport;

it('can create a defect type', function () {
    $defectType = DefectType::factory()->create([
        'name' => 'Mechanischer Defekt',
        'severity' => 'hoch',
    ]);

    expect($defectType)->toBeInstanceOf(DefectType::class)
        ->and($defectType->name)->toBe('Mechanischer Defekt')
        ->and($defectType->severity)->toBe('hoch');
});

it('has defect reports relationship', function () {
    $defectType = DefectType::factory()->create();
    $defectReport = DefectReport::factory()->create([
        'defect_type_id' => $defectType->id,
    ]);

    expect($defectType->defectReports)->toHaveCount(1)
        ->and($defectType->defectReports->first())->toBeInstanceOf(DefectReport::class);
});

it('can scope active defect types', function () {
    DefectType::factory()->create(['is_active' => true]);
    DefectType::factory()->create(['is_active' => false]);

    $activeDefectTypes = DefectType::active()->get();

    expect($activeDefectTypes)->toHaveCount(1)
        ->and($activeDefectTypes->first()->is_active)->toBeTrue();
});

it('has severity levels', function () {
    $severities = ['niedrig', 'mittel', 'hoch'];

    foreach ($severities as $severity) {
        $defectType = DefectType::factory()->create(['severity' => $severity]);
        expect($defectType->severity)->toBe($severity);
    }
});

it('stores description', function () {
    $defectType = DefectType::factory()->create([
        'description' => 'Defekt an beweglichen Teilen',
    ]);

    expect($defectType->description)->toBe('Defekt an beweglichen Teilen');
});
