<?php

use App\Models\Instrument;
use App\Models\InstrumentStatus;
use App\Models\InstrumentMovement;
use App\Models\User;
use App\Services\MovementService;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    $this->availableStatus = InstrumentStatus::factory()->create();
    
    $this->maintenanceStatus = InstrumentStatus::factory()->create();
    
    $this->instrument = Instrument::factory()->create([
        'status_id' => $this->availableStatus->id,
    ]);
});

it('logs a movement with status change', function () {
    $movement = MovementService::logMovement(
        instrument: $this->instrument,
        movementType: 'status_change',
        statusBefore: $this->availableStatus->id,
        statusAfter: $this->maintenanceStatus->id,
        notes: 'Moving to maintenance'
    );

    expect($movement)->toBeInstanceOf(InstrumentMovement::class)
        ->and($movement->instrument_id)->toBe($this->instrument->id)
        ->and($movement->movement_type)->toBe('status_change')
        ->and($movement->from_status)->toBe($this->availableStatus->id)
        ->and($movement->to_status)->toBe($this->maintenanceStatus->id)
        ->and($movement->notes)->toBe('Moving to maintenance');
    
    // Check that instrument status was updated
    expect($this->instrument->fresh()->status_id)->toBe($this->maintenanceStatus->id);
});

it('logs movement without updating instrument', function () {
    $originalStatus = $this->instrument->status_id;
    
    $movement = MovementService::logMovementOnly(
        instrument: $this->instrument,
        movementType: 'status_change',
        statusBefore: $this->availableStatus->id,
        statusAfter: $this->maintenanceStatus->id,
        notes: 'Log only test'
    );

    expect($movement)->toBeInstanceOf(InstrumentMovement::class)
        ->and($this->instrument->fresh()->status_id)->toBe($originalStatus);
});

it('uses current user by default', function () {
    $movement = MovementService::logMovement(
        instrument: $this->instrument,
        movementType: 'maintenance'
    );

    expect($movement->performed_by)->toBe($this->user->id);
});

it('can override the user', function () {
    $otherUser = User::factory()->create();
    
    $movement = MovementService::logMovement(
        instrument: $this->instrument,
        movementType: 'maintenance',
        movedBy: $otherUser->id
    );

    expect($movement->performed_by)->toBe($otherUser->id);
});

it('sets performed_at timestamp', function () {
    $movement = MovementService::logMovement(
        instrument: $this->instrument,
        movementType: 'status_change'
    );

    expect($movement->performed_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($movement->performed_at->isToday())->toBeTrue();
});

it('handles container changes', function () {
    $fromContainer = \App\Models\Container::factory()->create();
    $toContainer = \App\Models\Container::factory()->create();
    
    $movement = MovementService::logMovement(
        instrument: $this->instrument,
        movementType: 'container_assignment',
        fromContainerId: $fromContainer->id,
        toContainerId: $toContainer->id
    );

    expect($movement->from_container_id)->toBe($fromContainer->id)
        ->and($movement->to_container_id)->toBe($toContainer->id);
});

it('uses current status if not provided', function () {
    $movement = MovementService::logMovement(
        instrument: $this->instrument,
        movementType: 'location_change'
    );

    expect($movement->from_status)->toBe($this->availableStatus->id)
        ->and($movement->to_status)->toBe($this->availableStatus->id);
});

it('creates movement record in database', function () {
    expect(InstrumentMovement::count())->toBe(0);
    
    MovementService::logMovement(
        instrument: $this->instrument,
        movementType: 'status_change'
    );

    expect(InstrumentMovement::count())->toBe(1);
});

it('logs status change correctly', function () {
    $this->instrument->update(['status_id' => $this->availableStatus->id]);
    $this->instrument->refresh();
    
    // Update the instrument status to trigger status change
    $this->instrument->status_id = $this->maintenanceStatus->id;
    
    $movement = MovementService::logStatusChange(
        instrument: $this->instrument,
        newStatusId: $this->maintenanceStatus->id,
        notes: 'Status change test'
    );

    expect($movement->movement_type)->toBe('status_change')
        ->and($movement->from_status)->toBe($this->availableStatus->id)
        ->and($movement->to_status)->toBe($this->maintenanceStatus->id);
});
