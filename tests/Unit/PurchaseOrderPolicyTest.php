<?php

use App\Models\User;
use App\Models\PurchaseOrder;
use App\Policies\PurchaseOrderPolicy;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->purchasingStaff = User::factory()->create(['role' => 'purchasing_staff']);
    $this->user = User::factory()->create(['role' => 'user']);
    
    $this->policy = new PurchaseOrderPolicy();
    $this->purchaseOrder = PurchaseOrder::factory()->create();
});

it('allows admin to view any purchase order', function () {
    $canView = $this->policy->viewAny($this->admin);
    
    expect($canView)->toBeTrue();
});

it('allows purchasing staff to view any purchase order', function () {
    $canView = $this->policy->viewAny($this->purchasingStaff);
    
    expect($canView)->toBeTrue();
});

it('denies regular user to view all purchase orders', function () {
    $canView = $this->policy->viewAny($this->user);
    
    expect($canView)->toBeFalse();
});

it('allows admin to create purchase orders', function () {
    $canCreate = $this->policy->create($this->admin);
    
    expect($canCreate)->toBeTrue();
});

it('allows purchasing staff to create purchase orders', function () {
    $canCreate = $this->policy->create($this->purchasingStaff);
    
    expect($canCreate)->toBeTrue();
});

it('denies regular user to create purchase orders', function () {
    $canCreate = $this->policy->create($this->user);
    
    expect($canCreate)->toBeFalse();
});

it('allows admin to update purchase orders', function () {
    $canUpdate = $this->policy->update($this->admin, $this->purchaseOrder);
    
    expect($canUpdate)->toBeTrue();
});

it('allows purchasing staff to update purchase orders', function () {
    $canUpdate = $this->policy->update($this->purchasingStaff, $this->purchaseOrder);
    
    expect($canUpdate)->toBeTrue();
});

it('denies regular user to update purchase orders', function () {
    $canUpdate = $this->policy->update($this->user, $this->purchaseOrder);
    
    expect($canUpdate)->toBeFalse();
});

it('allows admin to delete purchase orders only if not yet received', function () {
    $unreceivedOrder = PurchaseOrder::factory()->create([
        'received_at' => null,
        'ordered_by' => $this->admin->id,
    ]);
    
    $canDelete = $this->policy->delete($this->admin, $unreceivedOrder);
    
    // Da die Tabelle kein approved_at hat, sollte es true sein
    expect($canDelete)->toBeTrue();
});

it('denies purchasing staff to delete purchase orders', function () {
    $canDelete = $this->policy->delete($this->purchasingStaff, $this->purchaseOrder);
    
    expect($canDelete)->toBeFalse();
});

it('denies regular user to delete purchase orders', function () {
    $canDelete = $this->policy->delete($this->user, $this->purchaseOrder);
    
    expect($canDelete)->toBeFalse();
});
