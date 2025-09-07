<?php

use App\Models\User;
use App\Models\Instrument;
use App\Models\InstrumentStatus;
use App\Models\InstrumentCategory;
use App\Models\Manufacturer;
use App\Livewire\Instruments\InstrumentsList;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    $this->category = InstrumentCategory::factory()->create();
    $this->manufacturer = Manufacturer::factory()->create();
    $this->status = InstrumentStatus::factory()->create([
        'name' => 'Verfügbar',
        'bg_class' => 'bg-green-100',
        'text_class' => 'text-green-800'
    ]);
});

it('can render instruments list', function () {
    $instruments = Instrument::factory(3)->create([
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => $this->status->id,
    ]);

    Livewire::test(InstrumentsList::class)
        ->assertSuccessful()
        ->assertSee($instruments->first()->name)
        ->assertSee($instruments->first()->serial_number);
});

it('can search instruments by name', function () {
    $searchableInstrument = Instrument::factory()->create([
        'name' => 'Searchable Scalpel',
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => $this->status->id,
    ]);
    
    $otherInstrument = Instrument::factory()->create([
        'name' => 'Other Tool',
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => $this->status->id,
    ]);

    Livewire::test(InstrumentsList::class)
        ->set('search', 'Searchable')
        ->assertSee('Searchable Scalpel')
        ->assertDontSee('Other Tool');
});

it('can search instruments by serial number', function () {
    $searchableInstrument = Instrument::factory()->create([
        'serial_number' => 'SEARCH-001',
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => $this->status->id,
    ]);
    
    $otherInstrument = Instrument::factory()->create([
        'serial_number' => 'OTHER-001',
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => $this->status->id,
    ]);

    Livewire::test(InstrumentsList::class)
        ->set('search', 'SEARCH')
        ->assertSee('SEARCH-001')
        ->assertDontSee('OTHER-001');
});

it('can filter by category', function () {
    $category1 = InstrumentCategory::factory()->create(['name' => 'Category 1']);
    $category2 = InstrumentCategory::factory()->create(['name' => 'Category 2']);
    
    $instrument1 = Instrument::factory()->create([
        'category_id' => $category1->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => $this->status->id,
    ]);
    
    $instrument2 = Instrument::factory()->create([
        'category_id' => $category2->id,
        'manufacturer_id' => $this->manufacturer->id,
        'status_id' => $this->status->id,
    ]);

    Livewire::test(InstrumentsList::class)
        ->set('categoryFilter', $category1->id)
        ->assertSee($instrument1->name)
        ->assertDontSee($instrument2->name);
});

it('can filter by status', function () {
    $status1 = InstrumentStatus::factory()->create(['name' => 'Status 1']);
    $status2 = InstrumentStatus::factory()->create(['name' => 'Status 2']);
    
    $instrument1 = Instrument::factory()->create([
        'status_id' => $status1->id,
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
    ]);
    
    $instrument2 = Instrument::factory()->create([
        'status_id' => $status2->id,
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
    ]);

    Livewire::test(InstrumentsList::class)
        ->set('statusFilter', $status1->id)
        ->assertSee($instrument1->name)
        ->assertDontSee($instrument2->name);
});

it('can update instrument status', function () {
    $instrument = Instrument::factory()->create([
        'status_id' => $this->status->id,
        'category_id' => $this->category->id,
        'manufacturer_id' => $this->manufacturer->id,
    ]);
    
    $newStatus = InstrumentStatus::factory()->create([
        'name' => 'In Reparatur',
        'bg_class' => 'bg-amber-100',
        'text_class' => 'text-amber-800'
    ]);

    Livewire::test(InstrumentsList::class)
        ->call('updateStatus', $instrument->id, $newStatus->id)
        ->assertSuccessful();

    expect($instrument->fresh()->status_id)->toBe($newStatus->id);
});

it('can reset filters', function () {
    Livewire::test(InstrumentsList::class)
        ->set('search', 'test search')
        ->set('categoryFilter', 1)
        ->set('statusFilter', 1)
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSet('categoryFilter', '')
        ->assertSet('statusFilter', '');
});
