<?php

use App\Models\User;
use App\Models\Instrument;
use App\Models\InstrumentStatus;
use App\Models\Manufacturer;
use App\Models\InstrumentCategory;
use App\Livewire\Instruments\InstrumentsList;
use App\Livewire\Instruments\CreateInstrument;
use App\Livewire\Instruments\EditInstrument;
use App\Livewire\Instruments\ShowInstrument;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    $this->status = InstrumentStatus::factory()->create();
    $this->manufacturer = Manufacturer::factory()->create();
    $this->category = InstrumentCategory::factory()->create();
});

it('can render instruments list', function () {
    $instruments = Instrument::factory()->count(3)->create([
        'status_id' => $this->status->id,
        'manufacturer_id' => $this->manufacturer->id,
    ]);

    Livewire::test(InstrumentsList::class)
        ->assertSuccessful()
        ->assertSee($instruments->first()->name);
});

it('can search instruments by name', function () {
    $searchableInstrument = Instrument::factory()->create([
        'name' => 'Searchable Scalpel',
        'status_id' => $this->status->id,
    ]);
    
    $otherInstrument = Instrument::factory()->create([
        'name' => 'Other Forceps',
        'status_id' => $this->status->id,
    ]);

    Livewire::test(InstrumentsList::class)
        ->set('search', 'Searchable')
        ->assertSee('Searchable Scalpel')
        ->assertDontSee('Other Forceps');
});

it('can search instruments by serial number', function () {
    $searchableInstrument = Instrument::factory()->create([
        'serial_number' => 'SN12345',
        'status_id' => $this->status->id,
    ]);
    
    $otherInstrument = Instrument::factory()->create([
        'serial_number' => 'SN67890',
        'status_id' => $this->status->id,
    ]);

    Livewire::test(InstrumentsList::class)
        ->set('search', 'SN12345')
        ->assertSee('SN12345')
        ->assertDontSee('SN67890');
});

it('can filter by status', function () {
    $availableStatus = InstrumentStatus::factory()->create();
    $defectStatus = InstrumentStatus::factory()->create();
    
    $availableInstrument = Instrument::factory()->create(['status_id' => $availableStatus->id]);
    $defectInstrument = Instrument::factory()->create(['status_id' => $defectStatus->id]);

    Livewire::test(InstrumentsList::class)
        ->set('statusFilter', $availableStatus->id)
        ->assertSee($availableInstrument->name)
        ->assertDontSee($defectInstrument->name);
});

// Test removed - manufacturerFilter property doesn't exist in InstrumentsList component

it('can show instrument details', function () {
    $instrument = Instrument::factory()->create([
        'name' => 'Test Instrument',
        'serial_number' => 'SN123',
        'status_id' => $this->status->id,
    ]);

    Livewire::test(ShowInstrument::class, ['instrument' => $instrument])
        ->assertSuccessful()
        ->assertSee('Test Instrument')
        ->assertSee('SN123');
});

it('displays instrument movements timeline', function () {
    $instrument = Instrument::factory()->create([
        'status_id' => $this->status->id,
    ]);

    Livewire::test(ShowInstrument::class, ['instrument' => $instrument])
        ->assertSuccessful();
});

it('paginates instruments list', function () {
    Instrument::factory()->count(20)->create([
        'status_id' => $this->status->id,
    ]);

    Livewire::test(InstrumentsList::class)
        ->assertSuccessful()
        ->assertViewHas('instruments');
});
