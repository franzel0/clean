<?php

namespace App\Livewire\Departments;

use App\Models\Department;
use Livewire\Component;

class ManageDepartmentsModal extends Component
{
    public $showModal = false;
    public $departments = [];
    public $departmentStates = [];

    public function mount()
    {
        $this->loadDepartments();
    }

    public function openModal()
    {
        $this->loadDepartments();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function loadDepartments()
    {
        $this->departments = Department::all();
        $this->departmentStates = $this->departments->mapWithKeys(function ($dept) {
            return [$dept->id => $dept->is_active];
        })->toArray();
    }

    public function toggleDepartment($departmentId)
    {
        $this->departmentStates[$departmentId] = !$this->departmentStates[$departmentId];
    }

    public function save()
    {
        foreach ($this->departmentStates as $departmentId => $isActive) {
            Department::find($departmentId)?->update(['is_active' => $isActive]);
        }

        session()->flash('message', 'Abteilungen aktualisiert');
        $this->closeModal();
        $this->dispatch('departmentsUpdated');
    }

    public function render()
    {
        return view('livewire.departments.manage-departments-modal');
    }
}
