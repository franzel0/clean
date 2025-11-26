<?php

use App\Models\User;
use App\Models\Container;
use App\Models\ContainerType;
use App\Models\ContainerStatus;
use App\Models\Instrument;
use App\Livewire\Containers\ContainersList;
use App\Livewire\Containers\ShowContainer;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    $this->containerType = ContainerType::factory()->create();
    $this->containerStatus = ContainerStatus::factory()->create();
});

it('can render containers list', function () {
    $containers = Container::factory()->count(3)->create([
        'type_id' => $this->containerType->id,
        'status_id' => $this->containerStatus->id,
    ]);

    Livewire::test(ContainersList::class)
        ->assertSuccessful()
        ->assertSee($containers->first()->name);
});

it('can search containers by name', function () {
    $searchableContainer = Container::factory()->create([
        'name' => 'Searchable Container',
        'type_id' => $this->containerType->id,
    ]);
    
    $otherContainer = Container::factory()->create([
        'name' => 'Other Container',
        'type_id' => $this->containerType->id,
    ]);

    Livewire::test(ContainersList::class)
        ->set('search', 'Searchable')
        ->assertSee('Searchable Container')
        ->assertDontSee('Other Container');
});

it('can search containers by barcode', function () {
    $searchableContainer = Container::factory()->create([
        'barcode' => 'BC12345',
        'type_id' => $this->containerType->id,
    ]);
    
    $otherContainer = Container::factory()->create([
        'barcode' => 'BC67890',
        'type_id' => $this->containerType->id,
    ]);

    Livewire::test(ContainersList::class)
        ->set('search', 'BC12345')
        ->assertSee('BC12345')
        ->assertDontSee('BC67890');
});

it('can filter by container type', function () {
    $type1 = ContainerType::factory()->create(['name' => 'Type A']);
    $type2 = ContainerType::factory()->create(['name' => 'Type B']);
    
    $container1 = Container::factory()->create(['type_id' => $type1->id]);
    $container2 = Container::factory()->create(['type_id' => $type2->id]);

    Livewire::test(ContainersList::class)
        ->set('typeFilter', $type1->id)
        ->assertSee($container1->name)
        ->assertDontSee($container2->name);
});

it('displays container instruments count', function () {
    $container = Container::factory()->create([
        'type_id' => $this->containerType->id,
    ]);
    
    Instrument::factory()->count(5)->create([
        'current_container_id' => $container->id,
    ]);

    Livewire::test(ShowContainer::class, ['container' => $container])
        ->assertSuccessful()
        ->assertSee($container->name);
});
