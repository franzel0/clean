<?php

use App\Models\OperatingRoom;
use App\Models\Department;
use App\Models\Instrument;

it('can create an operating room', function () {
    $operatingRoom = OperatingRoom::factory()->create([
        'name' => 'OP Saal 1',
        'code' => 'OP-001',
    ]);

    expect($operatingRoom)->toBeInstanceOf(OperatingRoom::class)
        ->and($operatingRoom->name)->toBe('OP Saal 1')
        ->and($operatingRoom->code)->toBe('OP-001');
});

it('can scope active operating rooms', function () {
    OperatingRoom::factory()->create(['is_active' => true]);
    OperatingRoom::factory()->create(['is_active' => false]);

    $activeRooms = OperatingRoom::active()->get();

    expect($activeRooms)->toHaveCount(1)
        ->and($activeRooms->first()->is_active)->toBeTrue();
});

it('has a department relationship', function () {
    $department = \App\Models\Department::factory()->create();
    $operatingRoom = OperatingRoom::factory()->create([
        'department_id' => $department->id,
    ]);

    expect($operatingRoom->department)->toBeInstanceOf(\App\Models\Department::class);
});

it('stores operating room location', function () {
    $operatingRoom = OperatingRoom::factory()->create([
        'location' => '2nd Floor, East Wing',
    ]);

    expect($operatingRoom->location)->toBe('2nd Floor, East Wing');
});

it('can be deactivated', function () {
    $operatingRoom = OperatingRoom::factory()->create(['is_active' => true]);

    $operatingRoom->update(['is_active' => false]);

    expect($operatingRoom->fresh()->is_active)->toBeFalse();
});
