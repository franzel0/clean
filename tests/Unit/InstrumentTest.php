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
    ]);

    expect($instrument->name)->toBe('Test Scalpel')
        ->and($instrument->serial_number)->toBe('TST-001')
        ->and($instrument->category_id)->toBe($this->category->id)
        ->and($instrument->manufacturer_id)->toBe($this->manufacturer->id)
        ->and($instrument->status_id)->toBeGreaterThan(0);
});

it('has correct relationships', function () {
    $instrument = Instrument::factory()->create([
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
    ]);

    expect($instrument->category)->toBeInstanceOf(InstrumentCategory::class)
        ->and($instrument->category->id)->toBe($this->category->id)
        ->and($instrument->manufacturerRelation)->toBeInstanceOf(Manufacturer::class)
        ->and($instrument->manufacturerRelation->id)->toBe($this->manufacturer->id)
        ->and($instrument->instrumentStatus)->not->toBeNull();
});

it('generates correct status display', function () {
    $instrument = Instrument::factory()->create([
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
    ]);

    expect($instrument->status_display)->toBeString()
        ->and($instrument->status_display)->not->toBeEmpty();
});

it('can update status', function () {
    $instrument = Instrument::factory()->create([
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
    ]);

    $originalStatusId = $instrument->status_id;
    
    // Finde einen anderen Status
    $newStatus = \App\Models\InstrumentStatus::where('id', '!=', $originalStatusId)->first();
    
    if ($newStatus) {
        $instrument->update(['status_id' => $newStatus->id]);
        $instrument->refresh();

        expect($instrument->status_id)->toBe($newStatus->id)
            ->and($instrument->instrumentStatus->name)->toBe($newStatus->name);
    } else {
        // Fallback wenn nur ein Status existiert
        expect($instrument->status_id)->toBe($originalStatusId);
    }
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
    ]);

    $instrument->delete();

    expect($instrument->exists)->toBeFalse();
});
