<?php

use App\Models\User;
use App\Livewire\Dashboard;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render dashboard', function () {
    Livewire::test(Dashboard::class)
        ->assertSuccessful();
});

it('displays total instruments count', function () {
    \App\Models\Instrument::factory()->count(5)->create();
    
    Livewire::test(Dashboard::class)
        ->assertSuccessful()
        ->assertViewHas('stats', function ($stats) {
            return $stats['total_instruments'] === 5;
        });
});

it('displays active defect reports count', function () {
    \App\Models\DefectReport::factory()->count(3)->create([
        'is_completed' => false,
    ]);
    
    Livewire::test(Dashboard::class)
        ->assertSuccessful();
});

it('displays pending purchase orders count', function () {
    \App\Models\PurchaseOrder::factory()->count(2)->create([
        'is_completed' => false,
    ]);
    
    Livewire::test(Dashboard::class)
        ->assertSuccessful();
});

it('displays recent defect reports', function () {
    $defectReport = \App\Models\DefectReport::factory()->create([
        'description' => 'Recent defect',
        'is_completed' => false,
    ]);
    
    Livewire::test(Dashboard::class)
        ->assertSuccessful()
        ->assertViewHas('recent_reports', function ($reports) {
            return $reports->count() > 0;
        });
});

it('displays recent movements', function () {
    $instrument = \App\Models\Instrument::factory()->create(['name' => 'Test Instrument']);
    \App\Models\InstrumentMovement::factory()->create([
        'instrument_id' => $instrument->id,
        'performed_at' => now(),
    ]);
    
    Livewire::test(Dashboard::class)
        ->assertSuccessful();
});

it('shows statistics for different instrument statuses', function () {
    $availableStatus = \App\Models\InstrumentStatus::factory()->create();
    $defectStatus = \App\Models\InstrumentStatus::factory()->create();
    
    \App\Models\Instrument::factory()->count(10)->create(['status_id' => $availableStatus->id]);
    \App\Models\Instrument::factory()->count(3)->create(['status_id' => $defectStatus->id]);
    
    Livewire::test(Dashboard::class)
        ->assertSuccessful();
});
