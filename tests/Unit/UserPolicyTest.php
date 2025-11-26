<?php

use App\Models\User;
use App\Policies\UserPolicy;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->manager = User::factory()->create(['role' => 'manager']);
    $this->user = User::factory()->create(['role' => 'user']);
    
    $this->policy = new UserPolicy();
});

it('allows admin to view any user', function () {
    $canViewAny = $this->policy->viewAny($this->admin);
    
    expect($canViewAny)->toBeTrue();
});

it('denies manager to view any user', function () {
    $canViewAny = $this->policy->viewAny($this->manager);
    
    expect($canViewAny)->toBeFalse();
});

it('denies regular user to view any user', function () {
    $canViewAny = $this->policy->viewAny($this->user);
    
    expect($canViewAny)->toBeFalse();
});

it('allows users to view their own profile', function () {
    $canView = $this->policy->view($this->user, $this->user);
    
    expect($canView)->toBeTrue();
});

it('allows admin to view other users', function () {
    $canView = $this->policy->view($this->admin, $this->user);
    
    expect($canView)->toBeTrue();
});

it('denies users to view other users profiles', function () {
    $otherUser = User::factory()->create();
    $canView = $this->policy->view($this->user, $otherUser);
    
    expect($canView)->toBeFalse();
});

it('allows admin to create users', function () {
    $canCreate = $this->policy->create($this->admin);
    
    expect($canCreate)->toBeTrue();
});

it('denies manager to create users', function () {
    $canCreate = $this->policy->create($this->manager);
    
    expect($canCreate)->toBeFalse();
});

it('denies regular user to create users', function () {
    $canCreate = $this->policy->create($this->user);
    
    expect($canCreate)->toBeFalse();
});

it('allows users to update their own profile', function () {
    $canUpdate = $this->policy->update($this->user, $this->user);
    
    expect($canUpdate)->toBeTrue();
});

it('allows admin to update any user', function () {
    $canUpdate = $this->policy->update($this->admin, $this->user);
    
    expect($canUpdate)->toBeTrue();
});

it('denies users to update other users', function () {
    $otherUser = User::factory()->create();
    $canUpdate = $this->policy->update($this->user, $otherUser);
    
    expect($canUpdate)->toBeFalse();
});

it('allows admin to delete users', function () {
    $canDelete = $this->policy->delete($this->admin, $this->user);
    
    expect($canDelete)->toBeTrue();
});

it('denies users to delete themselves', function () {
    $canDelete = $this->policy->delete($this->user, $this->user);
    
    expect($canDelete)->toBeFalse();
});

it('denies manager to delete users', function () {
    $canDelete = $this->policy->delete($this->manager, $this->user);
    
    expect($canDelete)->toBeFalse();
});
