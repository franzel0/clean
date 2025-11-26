<?php

use App\Models\InstrumentMovement;
use App\Models\Instrument;
use App\Models\Container;
use App\Models\User;
use App\Models\InstrumentStatus;
use App\Models\Department;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->instrument = Instrument::factory()->create();
    
    $this->fromStatus = InstrumentStatus::factory()->create();
    $this->toStatus = InstrumentStatus::factory()->create();
});

it('can create an instrument movement', function () {
    $movement = InstrumentMovement::factory()->create([
        'instrument_id' => $this->instrument->id,
        'movement_type' => 'status_change',
        'performed_by' => $this->user->id,
    ]);

    expect($movement)->toBeInstanceOf(InstrumentMovement::class)
        ->and($movement->movement_type)->toBe('status_change');
});

it('has instrument relationship', function () {
    $movement = InstrumentMovement::factory()->create([
        'instrument_id' => $this->instrument->id,
    ]);

    expect($movement->instrument)->toBeInstanceOf(Instrument::class)
        ->and($movement->instrument->id)->toBe($this->instrument->id);
});

it('has performed by user relationship', function () {
    $movement = InstrumentMovement::factory()->create([
        'performed_by' => $this->user->id,
    ]);

    expect($movement->performedBy)->toBeInstanceOf(User::class)
        ->and($movement->performedBy->id)->toBe($this->user->id);
});

it('has container relationships', function () {
    $fromContainer = Container::factory()->create();
    $toContainer = Container::factory()->create();

    $movement = InstrumentMovement::factory()->create([
        'from_container_id' => $fromContainer->id,
        'to_container_id' => $toContainer->id,
    ]);

    expect($movement->fromContainer)->toBeInstanceOf(Container::class)
        ->and($movement->toContainer)->toBeInstanceOf(Container::class)
        ->and($movement->fromContainer->id)->toBe($fromContainer->id)
        ->and($movement->toContainer->id)->toBe($toContainer->id);
});

it('has status relationships', function () {
    $movement = InstrumentMovement::factory()->create([
        'from_status' => $this->fromStatus->id,
        'to_status' => $this->toStatus->id,
    ]);

    expect($movement->statusBeforeObject)->toBeInstanceOf(InstrumentStatus::class)
        ->and($movement->statusAfterObject)->toBeInstanceOf(InstrumentStatus::class)
        ->and($movement->statusBeforeObject->id)->toBe($this->fromStatus->id)
        ->and($movement->statusAfterObject->id)->toBe($this->toStatus->id);
});

it('displays movement type correctly', function () {
    $movements = [
        'location_change' => 'Standortwechsel',
        'container_assignment' => 'Container-Zuweisung',
        'container_removal' => 'Container-Entfernung',
        'status_change' => 'Status-Änderung',
        'maintenance' => 'Wartung',
    ];

    foreach ($movements as $type => $display) {
        $movement = InstrumentMovement::factory()->create(['movement_type' => $type]);
        expect($movement->movement_type_display)->toBe($display);
    }
});

it('casts performed_at to datetime', function () {
    $movement = InstrumentMovement::factory()->create([
        'performed_at' => '2025-11-26 10:00:00',
    ]);

    expect($movement->performed_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

// Test removed - from_department_id and to_department_id columns don't exist

it('can store notes', function () {
    $movement = InstrumentMovement::factory()->create([
        'notes' => 'Status updated via purchase order',
    ]);

    expect($movement->notes)->toBe('Status updated via purchase order');
});

it('tracks status changes correctly', function () {
    $movement = InstrumentMovement::factory()->create([
        'movement_type' => 'status_change',
        'from_status' => $this->fromStatus->id,
        'to_status' => $this->toStatus->id,
    ]);

    expect($movement->from_status)->toBe($this->fromStatus->id)
        ->and($movement->to_status)->toBe($this->toStatus->id);
});
