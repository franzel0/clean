<?php

use App\Models\Instrument;
use App\Models\InstrumentCategory;
use App\Models\Manufacturer;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->category = InstrumentCategory::factory()->create();
    $this->manufacturer = Manufacturer::factory()->create();
});

it('can create an instrument', function () {
    $instrument = Instrument::factory()->create([
        'name' => 'Test Scalpel',
        'serial_number' => 'TST-001',
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => 1, // "Verfügbar" aus Migration
    ]);

    expect($instrument->name)->toBe('Test Scalpel')
        ->and($instrument->serial_number)->toBe('TST-001')
        ->and($instrument->category_id)->toBe($this->category->id)
        ->and($instrument->manufacturer_id)->toBe($this->manufacturer->id)
        ->and($instrument->status_id)->toBe(1);
});

it('has correct relationships', function () {
    $instrument = Instrument::factory()->create([
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => 1,
    ]);

    expect($instrument->category)->toBeInstanceOf(InstrumentCategory::class)
        ->and($instrument->category->id)->toBe($this->category->id)
        ->and($instrument->manufacturerRelation)->toBeInstanceOf(Manufacturer::class)
        ->and($instrument->manufacturerRelation->id)->toBe($this->manufacturer->id)
        ->and($instrument->instrumentStatus)->not->toBeNull()
        ->and($instrument->instrumentStatus->name)->toBe('Verfügbar');
});

it('generates correct status display', function () {
    $instrument = Instrument::factory()->create([
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => 1, // "Verfügbar"
    ]);

    expect($instrument->status_display)->toBe('Verfügbar');
});

it('can update status', function () {
    $instrument = Instrument::factory()->create([
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => 1, // "Verfügbar"
    ]);

    $instrument->update(['status_id' => 3]); // "Defekt gemeldet"
    $instrument->refresh();

    expect($instrument->status_id)->toBe(3)
        ->and($instrument->instrumentStatus->name)->toBe('Defekt gemeldet');
});

it('validates required fields', function () {
    expect(function () {
        Instrument::create([]);
    })->toThrow(\Illuminate\Database\QueryException::class);
});

it('can be soft deleted', function () {
    $instrument = Instrument::factory()->create([
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => 1,
    ]);

    $instrument->delete();

    expect($instrument->exists)->toBeFalse();
});
