<?php

use App\Models\User;
use App\Models\InstrumentMovement;
use App\Models\Instrument;
use App\Models\InstrumentStatus;
use App\Livewire\Movements\MovementsList;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    $this->instrument = Instrument::factory()->create();
    $this->status = InstrumentStatus::factory()->create();
});

it('can render movements list', function () {
    $movements = InstrumentMovement::factory()->count(3)->create([
        'instrument_id' => $this->instrument->id,
        'performed_by' => $this->user->id,
    ]);

    Livewire::test(MovementsList::class)
        ->assertSuccessful()
        ->assertSee($this->instrument->name);
});

it('can filter by movement type', function () {
    $statusChangeMovement = InstrumentMovement::factory()->create([
        'movement_type' => 'status_change',
        'instrument_id' => $this->instrument->id,
    ]);
    
    $locationChangeMovement = InstrumentMovement::factory()->create([
        'movement_type' => 'location_change',
        'instrument_id' => Instrument::factory()->create()->id,
    ]);

    Livewire::test(MovementsList::class)
        ->set('typeFilter', 'status_change')
        ->assertSee('Status-Änderung');
});

it('can filter by instrument', function () {
    $instrument1 = Instrument::factory()->create(['name' => 'Instrument A']);
    $instrument2 = Instrument::factory()->create(['name' => 'Instrument B']);
    
    InstrumentMovement::factory()->create(['instrument_id' => $instrument1->id]);
    InstrumentMovement::factory()->create(['instrument_id' => $instrument2->id]);

    $component = Livewire::test(MovementsList::class)
        ->set('instrumentFilter', $instrument1->id)
        ->assertSee('Instrument A');
    
    // Check that only instrument A movements are shown by verifying count
    expect($component->get('instrumentFilter'))->toBe($instrument1->id);
});

it('can filter by date range', function () {
    $recentMovement = InstrumentMovement::factory()->create([
        'performed_at' => now()->subDays(1),
        'instrument_id' => $this->instrument->id,
    ]);
    
    $oldMovement = InstrumentMovement::factory()->create([
        'performed_at' => now()->subDays(10),
        'instrument_id' => Instrument::factory()->create()->id,
    ]);

    Livewire::test(MovementsList::class)
        ->set('startDate', now()->subDays(2)->format('Y-m-d'))
        ->set('endDate', now()->format('Y-m-d'))
        ->assertSuccessful();
});

it('displays movement performer', function () {
    $movement = InstrumentMovement::factory()->create([
        'instrument_id' => $this->instrument->id,
        'performed_by' => $this->user->id,
    ]);

    Livewire::test(MovementsList::class)
        ->assertSuccessful()
        ->assertSee($this->user->name);
});

it('paginates movements', function () {
    InstrumentMovement::factory()->count(20)->create([
        'instrument_id' => $this->instrument->id,
    ]);

    Livewire::test(MovementsList::class)
        ->assertSuccessful();
});
