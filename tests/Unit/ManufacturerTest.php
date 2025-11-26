<?php

use App\Models\Manufacturer;
use App\Models\Instrument;
use App\Models\PurchaseOrder;

it('can create a manufacturer', function () {
    $manufacturer = Manufacturer::factory()->create([
        'name' => 'Test Manufacturer',
        'contact_email' => 'test@manufacturer.com',
    ]);

    expect($manufacturer)->toBeInstanceOf(Manufacturer::class)
        ->and($manufacturer->name)->toBe('Test Manufacturer')
        ->and($manufacturer->contact_email)->toBe('test@manufacturer.com');
});

it('has instruments relationship', function () {
    $manufacturer = Manufacturer::factory()->create();
    $instrument = Instrument::factory()->create([
        'manufacturer_id' => $manufacturer->id,
    ]);

    expect($manufacturer->instruments)->toHaveCount(1)
        ->and($manufacturer->instruments->first())->toBeInstanceOf(Instrument::class);
});

it('has purchase orders relationship', function () {
    $manufacturer = Manufacturer::factory()->create();
    $purchaseOrder = PurchaseOrder::factory()->create([
        'manufacturer_id' => $manufacturer->id,
    ]);

    expect($manufacturer->purchaseOrders)->toHaveCount(1)
        ->and($manufacturer->purchaseOrders->first())->toBeInstanceOf(PurchaseOrder::class);
});

it('can scope active manufacturers', function () {
    Manufacturer::factory()->create(['is_active' => true]);
    Manufacturer::factory()->create(['is_active' => false]);

    $activeManufacturers = Manufacturer::active()->get();

    expect($activeManufacturers)->toHaveCount(1)
        ->and($activeManufacturers->first()->is_active)->toBeTrue();
});

it('can scope ordered manufacturers', function () {
    Manufacturer::factory()->create(['name' => 'Zebra Manufacturer', 'sort_order' => 2]);
    Manufacturer::factory()->create(['name' => 'Alpha Manufacturer', 'sort_order' => 1]);

    $orderedManufacturers = Manufacturer::ordered()->get();

    expect($orderedManufacturers->first()->name)->toBe('Alpha Manufacturer')
        ->and($orderedManufacturers->last()->name)->toBe('Zebra Manufacturer');
});

it('stores contact information', function () {
    $manufacturer = Manufacturer::factory()->create([
        'contact_email' => 'contact@test.com',
        'contact_phone' => '+49 123 456789',
        'website' => 'https://test.com',
    ]);

    expect($manufacturer->contact_email)->toBe('contact@test.com')
        ->and($manufacturer->contact_phone)->toBe('+49 123 456789')
        ->and($manufacturer->website)->toBe('https://test.com');
});

it('can be deactivated', function () {
    $manufacturer = Manufacturer::factory()->create(['is_active' => true]);

    $manufacturer->update(['is_active' => false]);

    expect($manufacturer->fresh()->is_active)->toBeFalse();
});
