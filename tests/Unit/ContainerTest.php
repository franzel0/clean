<?php

use App\Models\Container;
use App\Models\ContainerType;
use App\Models\ContainerStatus;
use App\Models\Instrument;

beforeEach(function () {
    $this->containerType = ContainerType::factory()->create([
        'name' => 'Chirurgie-Set'
    ]);
    
    $this->containerStatus = ContainerStatus::factory()->create([
        'name' => 'Vollständig'
    ]);
});

it('can create a container', function () {
    $container = Container::factory()->create([
        'name' => 'Test Container',
        'type_id' => $this->containerType->id,
        'status_id' => $this->containerStatus->id,
    ]);

    expect($container)->toBeInstanceOf(Container::class)
        ->and($container->name)->toBe('Test Container')
        ->and($container->is_active)->toBeTrue();
});

it('has a container type relationship', function () {
    $container = Container::factory()->create([
        'type_id' => $this->containerType->id,
    ]);

    expect($container->containerType)->toBeInstanceOf(ContainerType::class)
        ->and($container->containerType->name)->toBe('Chirurgie-Set');
});

it('has a container status relationship', function () {
    $container = Container::factory()->create([
        'status_id' => $this->containerStatus->id,
    ]);

    expect($container->containerStatus)->toBeInstanceOf(ContainerStatus::class)
        ->and($container->containerStatus->name)->toBe('Vollständig');
});

it('has instruments relationship', function () {
    $container = Container::factory()->create();
    $instrument = Instrument::factory()->create([
        'current_container_id' => $container->id,
    ]);

    expect($container->instruments)->toHaveCount(1)
        ->and($container->instruments->first())->toBeInstanceOf(Instrument::class);
});

it('can scope active containers', function () {
    Container::factory()->create(['is_active' => true]);
    Container::factory()->create(['is_active' => false]);

    $activeContainers = Container::active()->get();

    expect($activeContainers)->toHaveCount(1)
        ->and($activeContainers->first()->is_active)->toBeTrue();
});

it('returns correct barcode display', function () {
    $containerWithBarcode = Container::factory()->create(['barcode' => 'BC12345']);
    $containerWithoutBarcode = Container::factory()->create(['barcode' => null]);

    expect($containerWithBarcode->barcode_display)->toBe('BC12345')
        ->and($containerWithoutBarcode->barcode_display)->toBe('Kein Barcode');
});

it('returns correct description display', function () {
    $containerWithDescription = Container::factory()->create(['description' => 'Test Description']);
    $containerWithoutDescription = Container::factory()->create(['description' => null]);

    expect($containerWithDescription->description_display)->toBe('Test Description')
        ->and($containerWithoutDescription->description_display)->toBe('Keine Beschreibung');
});

it('counts instruments correctly', function () {
    $container = Container::factory()->create();
    
    expect($container->instrument_count)->toBe(0);

    Instrument::factory()->count(3)->create(['current_container_id' => $container->id]);
    $container->refresh();

    expect($container->instrument_count)->toBe(3);
});

it('can be activated and deactivated', function () {
    $container = Container::factory()->create(['is_active' => false]);

    $container->update(['is_active' => true]);

    expect($container->fresh()->is_active)->toBeTrue();
});

it('validates required fields', function () {
    expect(fn() => Container::create([]))->toThrow(Exception::class);
});
