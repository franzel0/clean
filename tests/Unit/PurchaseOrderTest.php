<?php

use App\Models\PurchaseOrder;
use App\Models\DefectReport;
use App\Models\Manufacturer;
use App\Models\User;
use App\Models\Instrument;

it('can create a purchase order', function () {
    $purchaseOrder = PurchaseOrder::factory()->create();
    
    expect($purchaseOrder)->toBeInstanceOf(PurchaseOrder::class)
        ->and($purchaseOrder->order_number)->toBeString()
        ->and($purchaseOrder->order_date)->toBeInstanceOf(Carbon\Carbon::class)
        ->and($purchaseOrder->ordered_by)->toBeInt();
});

it('belongs to a defect report', function () {
    $defectReport = DefectReport::factory()->create();
    $purchaseOrder = PurchaseOrder::factory()->create([
        'defect_report_id' => $defectReport->id
    ]);
    
    expect($purchaseOrder->defectReport)->toBeInstanceOf(DefectReport::class)
        ->and($purchaseOrder->defectReport->id)->toBe($defectReport->id);
});

it('belongs to a manufacturer', function () {
    $manufacturer = Manufacturer::factory()->create();
    $purchaseOrder = PurchaseOrder::factory()->create([
        'manufacturer_id' => $manufacturer->id
    ]);
    
    expect($purchaseOrder->manufacturer)->toBeInstanceOf(Manufacturer::class)
        ->and($purchaseOrder->manufacturer->id)->toBe($manufacturer->id);
});

it('belongs to a user who ordered it', function () {
    $user = User::factory()->create();
    $purchaseOrder = PurchaseOrder::factory()->create([
        'ordered_by' => $user->id
    ]);
    
    expect($purchaseOrder->orderedBy)->toBeInstanceOf(User::class)
        ->and($purchaseOrder->orderedBy->id)->toBe($user->id);
});

it('can access instrument through defect report', function () {
    $instrument = Instrument::factory()->create();
    $defectReport = DefectReport::factory()->create([
        'instrument_id' => $instrument->id
    ]);
    $purchaseOrder = PurchaseOrder::factory()->create([
        'defect_report_id' => $defectReport->id
    ]);
    
    expect($purchaseOrder->defectReport->instrument)->toBeInstanceOf(Instrument::class)
        ->and($purchaseOrder->defectReport->instrument->id)->toBe($instrument->id);
});

it('generates unique order numbers', function () {
    $purchaseOrder1 = PurchaseOrder::factory()->create();
    $purchaseOrder2 = PurchaseOrder::factory()->create();
    
    expect($purchaseOrder1->order_number)->not->toBe($purchaseOrder2->order_number);
});

it('can be marked as delivered', function () {
    $purchaseOrder = PurchaseOrder::factory()->create([
        'received_at' => now(),
        'delivery_date' => now()
    ]);
    
    expect($purchaseOrder->received_at)->toBeInstanceOf(Carbon\Carbon::class)
        ->and($purchaseOrder->delivery_date)->toBeInstanceOf(Carbon\Carbon::class);
});

it('is not delivered by default', function () {
    $purchaseOrder = PurchaseOrder::factory()->create();
    
    expect($purchaseOrder->received_at)->toBeNull()
        ->and($purchaseOrder->delivery_date)->toBeNull();
});

it('validates required fields', function () {
    expect(function () {
        PurchaseOrder::create([]);
    })->toThrow(Exception::class);
});

it('can store optional fields', function () {
    $purchaseOrder = PurchaseOrder::factory()->create([
        'total_amount' => 299.99,
        'expected_delivery' => now()->addDays(10),
        'notes' => 'Test notes'
    ]);
    
    expect($purchaseOrder->total_amount)->toBe('299.99')
        ->and($purchaseOrder->expected_delivery)->toBeInstanceOf(Carbon\Carbon::class)
        ->and($purchaseOrder->notes)->toBe('Test notes');
});