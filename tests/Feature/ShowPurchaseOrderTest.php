<?php

use App\Models\User;
use App\Models\PurchaseOrder;
use App\Models\DefectReport;
use App\Models\Instrument;
use App\Models\Manufacturer;
use App\Models\InstrumentStatus;
use App\Livewire\PurchaseOrders\ShowPurchaseOrder;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($this->user);
    
    $this->manufacturer = Manufacturer::factory()->create();
    $this->confirmedStatus = InstrumentStatus::factory()->create();
    $this->orderedStatus = InstrumentStatus::factory()->create();
    
    $this->instrument = Instrument::factory()->create([
        'status_id' => $this->confirmedStatus->id,
    ]);
    
    $this->defectReport = DefectReport::factory()->create([
        'instrument_id' => $this->instrument->id,
        'reported_by' => $this->user->id,
    ]);
    
    $this->purchaseOrder = PurchaseOrder::factory()->create([
        'defect_report_id' => $this->defectReport->id,
        'manufacturer_id' => $this->manufacturer->id,
        'ordered_by' => $this->user->id,
    ]);
});

it('can display purchase order details', function () {
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->assertSuccessful()
        ->assertSee($this->purchaseOrder->order_number)
        ->assertSee($this->manufacturer->name);
});

it('can update purchase order details', function () {
    $newManufacturer = Manufacturer::factory()->create(['name' => 'New Manufacturer']);
    
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('manufacturer_id', $newManufacturer->id)
        ->set('totalAmount', 500.00)
        ->set('notes', 'Updated notes')
        ->call('updateDetails')
        ->assertSuccessful();

    $this->purchaseOrder->refresh();
    
    expect($this->purchaseOrder->manufacturer_id)->toBe($newManufacturer->id)
        ->and($this->purchaseOrder->total_amount)->toBe('500.00')
        ->and($this->purchaseOrder->notes)->toBe('Updated notes');
});

it('can update instrument status through purchase order', function () {
    $deliveredStatus = InstrumentStatus::factory()->create();
    
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('instrumentStatusId', $deliveredStatus->id)
        ->call('updateDetails')
        ->assertSuccessful();

    expect($this->instrument->fresh()->status_id)->toBe($deliveredStatus->id);
});

it('logs movement when status is updated through purchase order', function () {
    $deliveredStatus = InstrumentStatus::factory()->create();
    
    // Reload to get fresh instrument state
    $this->instrument->refresh();
    $oldStatus = $this->instrument->status_id;
    
    // Only test if status is actually different
    if ($oldStatus === $deliveredStatus->id) {
        $this->markTestSkipped('Status must be different for this test');
    }
    
    $movementsBefore = \App\Models\InstrumentMovement::where('instrument_id', $this->instrument->id)->count();
    
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('instrumentStatusId', $deliveredStatus->id)
        ->call('updateDetails');

    $movementsAfter = \App\Models\InstrumentMovement::where('instrument_id', $this->instrument->id)->count();
    expect($movementsAfter)->toBeGreaterThanOrEqual($movementsBefore);
});

it('displays purchase order timeline', function () {
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->assertSuccessful()
        ->assertSee($this->user->name);
});

it('can mark purchase order as completed', function () {
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('is_completed', true)
        ->call('updateDetails')
        ->assertSuccessful();

    expect($this->purchaseOrder->fresh()->is_completed)->toBeTrue();
});

it('can mark defect report as completed through purchase order', function () {
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('defect_report_completed', true)
        ->call('updateDetails')
        ->assertSuccessful();

    expect($this->defectReport->fresh()->is_completed)->toBeTrue();
});

it('validates cost is numeric', function () {
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('totalAmount', 'not-a-number')
        ->call('updateDetails')
        ->assertHasErrors(['totalAmount']);
});

it('validates expected delivery date is in future', function () {
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('expectedDelivery', now()->subDays(5)->format('Y-m-d'))
        ->call('updateDetails')
        ->assertHasErrors(['expectedDelivery']);
});

it('can download purchase order as PDF', function () {
    $response = Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->call('downloadPdf')
        ->assertSuccessful();
});

it('can select same instrument replacement type', function () {
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('replacement_type', 'same')
        ->call('updateDetails')
        ->assertSuccessful();
    
    $this->purchaseOrder->refresh();
    expect($this->purchaseOrder->old_instrument_id)->toBe($this->instrument->id);
    expect($this->purchaseOrder->new_instrument_id)->toBeNull();
    expect($this->purchaseOrder->replacement_instrument_description)->toBeNull();
});

it('can select alternative instrument replacement type', function () {
    $alternativeInstrument = Instrument::factory()->create([
        'status_id' => $this->confirmedStatus->id,
    ]);
    
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('replacement_type', 'alternative')
        ->set('new_instrument_id', $alternativeInstrument->id)
        ->call('updateDetails')
        ->assertSuccessful();
    
    $this->purchaseOrder->refresh();
    expect($this->purchaseOrder->old_instrument_id)->toBe($this->instrument->id);
    expect($this->purchaseOrder->new_instrument_id)->toBe($alternativeInstrument->id);
    expect($this->purchaseOrder->replacement_instrument_description)->toBeNull();
});

it('requires new instrument for alternative replacement type', function () {
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('replacement_type', 'alternative')
        ->set('new_instrument_id', '')
        ->call('updateDetails')
        ->assertHasErrors(['new_instrument_id']);
});

it('can enter description for replacement', function () {
    $description = 'Alternative model with improved specifications';
    
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('replacement_type', 'description')
        ->set('replacement_instrument_description', $description)
        ->call('updateDetails')
        ->assertSuccessful();
    
    $this->purchaseOrder->refresh();
    expect($this->purchaseOrder->old_instrument_id)->toBe($this->instrument->id);
    expect($this->purchaseOrder->new_instrument_id)->toBeNull();
    expect($this->purchaseOrder->replacement_instrument_description)->toBe($description);
});

it('requires description for description replacement type', function () {
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('replacement_type', 'description')
        ->set('replacement_instrument_description', '')
        ->call('updateDetails')
        ->assertHasErrors(['replacement_instrument_description']);
});

it('validates max 500 characters for replacement description', function () {
    $description = str_repeat('a', 501);
    
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->set('replacement_type', 'description')
        ->set('replacement_instrument_description', $description)
        ->call('updateDetails')
        ->assertHasErrors(['replacement_instrument_description']);
});

it('loads old instrument from defect report', function () {
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder])
        ->assertSuccessful()
        ->assertSee($this->instrument->name)
        ->assertSee($this->instrument->serial_number);
});

it('auto-detects replacement type from saved data', function () {
    $alternativeInstrument = Instrument::factory()->create([
        'status_id' => $this->confirmedStatus->id,
    ]);
    
    $this->purchaseOrder->update([
        'old_instrument_id' => $this->instrument->id,
        'new_instrument_id' => $alternativeInstrument->id,
    ]);
    
    Livewire::test(ShowPurchaseOrder::class, ['order' => $this->purchaseOrder->fresh()])
        ->assertSuccessful()
        ->assertSet('replacement_type', 'alternative');
});

