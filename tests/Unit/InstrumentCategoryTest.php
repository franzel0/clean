<?php

use App\Models\InstrumentCategory;
use App\Models\Instrument;

it('can create an instrument category', function () {
    $category = InstrumentCategory::factory()->create([
        'name' => 'Surgical Instruments',
        'description' => 'Instruments used in surgery',
    ]);

    expect($category)->toBeInstanceOf(InstrumentCategory::class)
        ->and($category->name)->toBe('Surgical Instruments')
        ->and($category->description)->toBe('Instruments used in surgery');
});

it('has instruments relationship', function () {
    $category = InstrumentCategory::factory()->create();
    $instrument = Instrument::factory()->create([
        'category_id' => $category->id,
    ]);

    expect($category->instruments)->toHaveCount(1)
        ->and($category->instruments->first())->toBeInstanceOf(Instrument::class);
});

it('can scope active categories', function () {
    InstrumentCategory::factory()->create(['is_active' => true]);
    InstrumentCategory::factory()->create(['is_active' => false]);

    $activeCategories = InstrumentCategory::active()->get();

    expect($activeCategories)->toHaveCount(1)
        ->and($activeCategories->first()->is_active)->toBeTrue();
});

it('can scope ordered categories', function () {
    InstrumentCategory::factory()->create(['name' => 'Zebra Category', 'sort_order' => 2]);
    InstrumentCategory::factory()->create(['name' => 'Alpha Category', 'sort_order' => 1]);

    $orderedCategories = InstrumentCategory::ordered()->get();

    expect($orderedCategories->first()->name)->toBe('Alpha Category')
        ->and($orderedCategories->last()->name)->toBe('Zebra Category');
});

it('can be deactivated', function () {
    $category = InstrumentCategory::factory()->create(['is_active' => true]);

    $category->update(['is_active' => false]);

    expect($category->fresh()->is_active)->toBeFalse();
});

it('can be deactivated and reactivated', function () {
    $category = InstrumentCategory::factory()->create(['is_active' => true]);
    $category->update(['is_active' => false]);
    expect($category->fresh()->is_active)->toBeFalse();
    
    $category->update(['is_active' => true]);
    expect($category->fresh()->is_active)->toBeTrue();
});
