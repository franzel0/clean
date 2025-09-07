<?php

use App\Models\User;
use App\Models\DefectReport;
use App\Models\Manufacturer;
use App\Models\Instrument;
use App\Models\InstrumentCategory;
use App\Models\InstrumentStatus;
use App\Models\PurchaseOrder;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    $this->manufacturer = Manufacturer::factory()->create();
    $this->category = InstrumentCategory::factory()->create();
    
    // Erstelle "Defekt bestätigt" Status wenn nicht vorhanden
    $this->confirmedStatus = InstrumentStatus::firstOrCreate([
        'name' => 'Defekt bestätigt'
    ], [
        'color' => '#dc2626',
        'bg_class' => 'bg-red-100',
        'text_class' => 'text-red-800',
        'sort_order' => 4,
    ]);
    
    // Erstelle Instrument mit "Defekt bestätigt" Status
    $this->instrument = Instrument::factory()->create([
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => $this->confirmedStatus->id,
    ]);
    
    $this->defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
    ]);
    
    // Stelle sicher, dass das Instrument den richtigen Status hat
    $this->instrument->refresh();
    $this->defectReport->refresh();
    $this->defectReport->load('instrument');
});

it('can render create purchase order page', function () {
    Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)
        ->assertSee('Neue Bestellung')
        ->assertSee('Defektmeldung auswählen')
        ->assertSee('Hersteller')
        ->assertSee('Neuer Instrumentenstatus');
});

it('loads defect reports with confirmed status', function () {
    // Manuell das Instrument auf den richtigen Status setzen
    $this->instrument->update(['status_id' => $this->confirmedStatus->id]);
    $this->instrument->refresh();
    $this->defectReport->refresh();
    $this->defectReport->load('instrument');
    
    // Debug: Prüfe dass das DefectReport existiert und richtig verknüpft ist
    expect($this->defectReport)->not->toBeNull()
        ->and($this->defectReport->instrument_id)->toBe($this->instrument->id)
        ->and($this->defectReport->instrument->status_id)->toBe($this->confirmedStatus->id);
    
    // Debug: Einfacher Query ohne Order By
    $confirmedStatusId = $this->confirmedStatus->id;
    $simpleQuery = \App\Models\DefectReport::whereHas('instrument', function ($query) use ($confirmedStatusId) {
        $query->where('status_id', $confirmedStatusId);
    })->get();
    
    expect($simpleQuery)->toBeCollection()
        ->and($simpleQuery->count())->toBeGreaterThan(0);
    
    $component = Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class);
    
    // Debug: Prüfe ob defectReports geladen wurden
    $defectReports = $component->get('defectReports');
    
    expect($defectReports)->toBeCollection()
        ->and($defectReports->count())->toBeGreaterThan(0)
        ->and($defectReports->contains('id', $this->defectReport->id))->toBeTrue();
});

it('can create a purchase order successfully', function () {
    // Erstelle "Ersatz bestellt" Status
    $replacementStatus = InstrumentStatus::firstOrCreate([
        'name' => 'Ersatz bestellt'
    ], [
        'color' => '#d97706',
        'bg_class' => 'bg-yellow-100', 
        'text_class' => 'text-yellow-800',
        'sort_order' => 5,
    ]);

    Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)
        ->set('defect_report_id', $this->defectReport->id)
        ->set('manufacturer_id', $this->manufacturer->id)
        ->set('estimated_cost', 250.00)
        ->set('estimated_delivery_date', now()->addDays(14)->format('Y-m-d'))
        ->set('notes', 'Dringend benötigt')
        ->set('status_id', $replacementStatus->id)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('purchase-orders.index'));

    // Prüfe ob Purchase Order erstellt wurde
    $this->assertDatabaseHas('purchase_orders', [
        'defect_report_id' => $this->defectReport->id,
        'manufacturer_id' => $this->manufacturer->id,
        'total_amount' => '250.00',
        'notes' => 'Dringend benötigt',
        'ordered_by' => $this->user->id,
    ]);

    // Prüfe ob Instrument Status aktualisiert wurde
    $this->instrument->refresh();
    expect($this->instrument->status_id)->toBe($replacementStatus->id);
});

it('validates required fields', function () {
    Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)
        ->call('save')
        ->assertHasErrors([
            'defect_report_id' => 'required',
            'manufacturer_id' => 'required'
        ]);
});

it('validates defect report exists', function () {
    Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)
        ->set('defect_report_id', 99999)
        ->set('manufacturer_id', $this->manufacturer->id)
        ->call('save')
        ->assertHasErrors(['defect_report_id' => 'exists']);
});

it('validates manufacturer exists', function () {
    Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)
        ->set('defect_report_id', $this->defectReport->id)
        ->set('manufacturer_id', 99999)
        ->call('save')
        ->assertHasErrors(['manufacturer_id' => 'exists']);
});

it('validates estimated cost is numeric', function () {
    Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)
        ->set('defect_report_id', $this->defectReport->id)
        ->set('manufacturer_id', $this->manufacturer->id)
        ->set('estimated_cost', 'not-a-number')
        ->call('save')
        ->assertHasErrors(['estimated_cost' => 'numeric']);
});

it('validates estimated cost is not negative', function () {
    Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)
        ->set('defect_report_id', $this->defectReport->id)
        ->set('manufacturer_id', $this->manufacturer->id)
        ->set('estimated_cost', -100)
        ->call('save')
        ->assertHasErrors(['estimated_cost' => 'min']);
});

it('validates delivery date format', function () {
    Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)
        ->set('defect_report_id', $this->defectReport->id)
        ->set('manufacturer_id', $this->manufacturer->id)
        ->set('estimated_delivery_date', 'not-a-date')
        ->call('save')
        ->assertHasErrors(['estimated_delivery_date' => 'date']);
});

it('validates notes max length', function () {
    $longNotes = str_repeat('a', 1001);
    
    Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)
        ->set('defect_report_id', $this->defectReport->id)
        ->set('manufacturer_id', $this->manufacturer->id)
        ->set('notes', $longNotes)
        ->call('save')
        ->assertHasErrors(['notes' => 'max']);
});

it('can create purchase order without optional fields', function () {
    // Manuell das Instrument auf den richtigen Status setzen
    $this->instrument->update(['status_id' => $this->confirmedStatus->id]);
    
    $component = Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)
        ->set('defect_report_id', $this->defectReport->id)
        ->set('manufacturer_id', $this->manufacturer->id);
    
    // Prüfe dass status_id leer ist (Standardverhalten)
    expect($component->get('status_id'))->toBe('');
    
    $component->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('purchase-orders.index'));

    // Prüfe ob Purchase Order mit Defaults erstellt wurde
    $this->assertDatabaseHas('purchase_orders', [
        'defect_report_id' => $this->defectReport->id,
        'manufacturer_id' => $this->manufacturer->id,
        'total_amount' => null,
        'expected_delivery' => null,
        'notes' => '',
        'ordered_by' => $this->user->id,
    ]);
});

it('does not show defect reports that already have purchase orders', function () {
    // Erstelle Purchase Order für defect report
    PurchaseOrder::factory()->create([
        'defect_report_id' => $this->defectReport->id,
        'manufacturer_id' => $this->manufacturer->id,
        'ordered_by' => $this->user->id,
    ]);

    Livewire::test(\App\Livewire\PurchaseOrders\CreatePurchaseOrder::class)
        ->assertSet('defectReports', function ($defectReports) {
            return !$defectReports->contains('id', $this->defectReport->id);
        });
});
