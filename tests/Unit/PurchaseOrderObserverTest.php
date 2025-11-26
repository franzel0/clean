<?php

use App\Models\PurchaseOrder;
use App\Models\DefectReport;
use App\Models\Instrument;
use App\Models\InstrumentStatus;
use App\Models\User;
use App\Observers\PurchaseOrderObserver;
use App\Services\InstrumentStatusService;

uses()->group('sequential');

beforeEach(function () {
    $this->user = User::factory()->create();
    
    // Use firstOrCreate to avoid UNIQUE constraint violations
    $this->confirmedStatus = InstrumentStatus::firstOrCreate(
        ['name' => 'Defekt bestätigt'],
        ['color' => '#ff9900', 'is_active' => true]
    );
    $this->orderedStatus = InstrumentStatus::firstOrCreate(
        ['name' => 'Ersatz bestellt'],
        ['color' => '#ffff00', 'is_active' => true]
    );
    $this->deliveredStatus = InstrumentStatus::firstOrCreate(
        ['name' => 'Ersatz geliefert'],
        ['color' => '#00ff00', 'is_active' => true]
    );
    
    $this->instrument = Instrument::factory()->create([
        'status_id' => $this->confirmedStatus->id,
    ]);
});

it('updates instrument status when purchase order is created', function () {
    $defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
    ]);
    
    $purchaseOrder = PurchaseOrder::factory()->create([
        'defect_report_id' => $defectReport->id,
        'ordered_by' => $this->user->id,
    ]);

    expect($this->instrument->fresh()->status_id)->toBe($this->orderedStatus->id);
});

it('updates instrument status when purchase order is delivered', function () {
    $defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
    ]);
    
    $purchaseOrder = PurchaseOrder::factory()->create([
        'defect_report_id' => $defectReport->id,
        'ordered_by' => $this->user->id,
        'is_delivered' => false,
    ]);
    
    $purchaseOrder->update(['is_delivered' => true]);

    expect($this->instrument->fresh()->status_id)->toBe($this->deliveredStatus->id);
});

it('updates instrument status when received_at is set', function () {
    $defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
    ]);
    
    $purchaseOrder = PurchaseOrder::factory()->create([
        'defect_report_id' => $defectReport->id,
        'ordered_by' => $this->user->id,
        'received_at' => null,
    ]);
    
    $purchaseOrder->update(['received_at' => now()]);

    expect($this->instrument->fresh()->status_id)->toBe($this->deliveredStatus->id);
});

it('does not update status if purchase order has no defect report', function () {
    $originalStatus = $this->instrument->status_id;
    
    PurchaseOrder::factory()->create([
        'defect_report_id' => null,
        'ordered_by' => $this->user->id,
    ]);

    expect($this->instrument->fresh()->status_id)->toBe($originalStatus);
});
