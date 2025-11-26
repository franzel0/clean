<?php

use App\Models\User;
use App\Models\Instrument;
use App\Models\InstrumentStatus;
use App\Models\InstrumentCategory;
use App\Models\Manufacturer;
use App\Models\OperatingRoom;

it('can create an instrument', function () {
    $status = InstrumentStatus::factory()->create();
    $manufacturer = Manufacturer::factory()->create();
    $category = InstrumentCategory::factory()->create();
    
    $instrument = Instrument::factory()->create([
        'name' => 'Test Scalpel',
        'serial_number' => 'SN12345',
        'status_id' => $status->id,
        'manufacturer_id' => $manufacturer->id,
        'category_id' => $category->id,
    ]);

    expect($instrument)->toBeInstanceOf(Instrument::class)
        ->and($instrument->name)->toBe('Test Scalpel')
        ->and($instrument->serial_number)->toBe('SN12345');
});

it('has status relationship', function () {
    $status = InstrumentStatus::factory()->create();
    $instrument = Instrument::factory()->create(['status_id' => $status->id]);

    expect($instrument->instrumentStatus)->toBeInstanceOf(InstrumentStatus::class)
        ->and($instrument->instrumentStatus->id)->toBe($status->id);
});

it('has manufacturer relationship', function () {
    $manufacturer = Manufacturer::factory()->create(['name' => 'Test Manufacturer']);
    $instrument = Instrument::factory()->create(['manufacturer_id' => $manufacturer->id]);

    expect($instrument->manufacturer)->toBeInstanceOf(Manufacturer::class)
        ->and($instrument->manufacturer->name)->toBe('Test Manufacturer');
});

it('has category relationship', function () {
    $category = InstrumentCategory::factory()->create(['name' => 'Surgical']);
    $instrument = Instrument::factory()->create(['category_id' => $category->id]);

    expect($instrument->instrumentCategory)->toBeInstanceOf(InstrumentCategory::class)
        ->and($instrument->instrumentCategory->name)->toBe('Surgical');
});

it('has defect reports relationship', function () {
    $instrument = Instrument::factory()->create();
    $defectReport = \App\Models\DefectReport::factory()->create([
        'instrument_id' => $instrument->id,
    ]);

    expect($instrument->defectReports)->toHaveCount(1)
        ->and($instrument->defectReports->first())->toBeInstanceOf(\App\Models\DefectReport::class);
});

it('has movements relationship', function () {
    $instrument = Instrument::factory()->create();
    $movement = \App\Models\InstrumentMovement::factory()->create([
        'instrument_id' => $instrument->id,
    ]);

    expect($instrument->movements)->toHaveCount(1)
        ->and($instrument->movements->first())->toBeInstanceOf(\App\Models\InstrumentMovement::class);
});

it('can scope active instruments', function () {
    Instrument::factory()->create(['is_active' => true]);
    Instrument::factory()->create(['is_active' => false]);

    $activeInstruments = Instrument::active()->get();

    expect($activeInstruments)->toHaveCount(1)
        ->and($activeInstruments->first()->is_active)->toBeTrue();
});

it('can scope by status', function () {
    $availableStatus = InstrumentStatus::factory()->create();
    $defectStatus = InstrumentStatus::factory()->create();
    
    Instrument::factory()->create(['status_id' => $availableStatus->id]);
    Instrument::factory()->create(['status_id' => $defectStatus->id]);

    $availableInstruments = Instrument::whereHas('instrumentStatus', function($q) use ($availableStatus) {
        $q->where('id', $availableStatus->id);
    })->get();

    expect($availableInstruments)->toHaveCount(1);
});

it('stores purchase date', function () {
    $instrument = Instrument::factory()->create([
        'purchase_date' => '2025-01-01',
    ]);

    expect($instrument->purchase_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('stores warranty expiration date', function () {
    $instrument = Instrument::factory()->create([
        'warranty_expires_at' => '2026-01-01',
    ]);

    expect($instrument->warranty_expires_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('can belong to a container', function () {
    $container = \App\Models\Container::factory()->create();
    $instrument = Instrument::factory()->create([
        'current_container_id' => $container->id,
    ]);

    expect($instrument->container)->toBeInstanceOf(\App\Models\Container::class)
        ->and($instrument->container->id)->toBe($container->id);
});

it('can belong to an operating room', function () {
    $operatingRoom = OperatingRoom::factory()->create();
    $instrument = Instrument::factory()->create([
        'current_operating_room_id' => $operatingRoom->id,
    ]);

    expect($instrument->operatingRoom)->toBeInstanceOf(OperatingRoom::class)
        ->and($instrument->operatingRoom->id)->toBe($operatingRoom->id);
});

it('validates serial number uniqueness', function () {
    Instrument::factory()->create(['serial_number' => 'UNIQUE123']);
    
    expect(fn() => Instrument::factory()->create(['serial_number' => 'UNIQUE123']))
        ->toThrow(Exception::class);
});
