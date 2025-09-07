<?php

use App\Livewire\PurchaseOrders\CreatePurchaseOrder;
use App\Models\DefectReport;
use App\Models\Instrument;
use App\Models\Manufacturer;
use App\Models\PurchaseOrder;
use App\Models\User;
use Livewire\Livewire;

it('can render create purchase order component', function () {
    $user = User::factory()->create();
    
    actingAs($user);
    
    Livewire::test(CreatePurchaseOrder::class)
        ->assertStatus(200);
});

it('can create purchase order and update instrument status', function () {
    $user = User::factory()->create();
    $manufacturer = Manufacturer::factory()->create();
    $instrument = Instrument::factory()->create();
    $defectReport = DefectReport::factory()->create(['instrument_id' => $instrument->id]);
    
    // Setze Instrument auf "Defekt bestätigt"
    $confirmedStatus = \App\Models\InstrumentStatus::where('name', 'Defekt bestätigt')->first();
    $ersatzBestelltStatus = \App\Models\InstrumentStatus::where('name', 'Ersatz bestellt')->first();
    
    $instrument->update(['status_id' => $confirmedStatus->id]);
    
    actingAs($user);
    
    Livewire::test(CreatePurchaseOrder::class)
        ->set('defect_report_id', $defectReport->id)
        ->set('manufacturer_id', $manufacturer->id)
        ->set('estimated_cost', 250.00)
        ->set('status_id', $ersatzBestelltStatus->id) // Setze Instrument Status auf "Ersatz bestellt"
        ->call('save')
        ->assertRedirect(route('purchase-orders.index'));
    
    // Prüfe dass Purchase Order erstellt wurde
    expect(PurchaseOrder::where('defect_report_id', $defectReport->id)->exists())->toBeTrue();
    
    // Prüfe dass Instrument Status aktualisiert wurde
    expect($instrument->fresh()->status_id)->toBe($ersatzBestelltStatus->id)
        ->and($instrument->fresh()->instrumentStatus->name)->toBe('Ersatz bestellt');
});

it('validates required fields', function () {
    $user = User::factory()->create();
    
    actingAs($user);
    
    Livewire::test(CreatePurchaseOrder::class)
        ->call('save')
        ->assertHasErrors(['defect_report_id', 'manufacturer_id']);
});

it('only shows defect reports with confirmed instrument status', function () {
    $user = User::factory()->create();
    $confirmedStatus = \App\Models\InstrumentStatus::where('name', 'Defekt bestätigt')->first();
    $availableStatus = \App\Models\InstrumentStatus::where('name', 'Verfügbar')->first();
    
    // Erstelle Instrumente mit unterschiedlichen Status
    $confirmedInstrument = Instrument::factory()->create(['status_id' => $confirmedStatus->id]);
    $availableInstrument = Instrument::factory()->create(['status_id' => $availableStatus->id]);
    
    $confirmedDefectReport = DefectReport::factory()->create(['instrument_id' => $confirmedInstrument->id]);
    $availableDefectReport = DefectReport::factory()->create(['instrument_id' => $availableInstrument->id]);
    
    actingAs($user);
    
    $component = Livewire::test(CreatePurchaseOrder::class);
    
    // Nur Defektmeldung mit "Defekt bestätigt" Status sollte verfügbar sein
    expect($component->get('defectReports')->pluck('id')->toArray())
        ->toContain($confirmedDefectReport->id)
        ->not->toContain($availableDefectReport->id);
});
