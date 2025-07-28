<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('components.layouts.app')]
#[Title('Benutzerverwaltung')]
class UsersIndex extends Component
{
    use WithPagination, AuthorizesRequests;

    public $search = '';
    public $roleFilter = '';
    public $departmentFilter = '';
    public $statusFilter = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingUser = null;

    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'user';
    public $department_id = '';
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|in:admin,sterilization_staff,or_staff,purchasing_staff,user',
        'department_id' => 'nullable|exists:departments,id',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->authorize('viewAny', User::class);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->roleFilter = '';
        $this->departmentFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->authorize('create', User::class);
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(User $user)
    {
        $this->authorize('update', $user);
        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->department_id = $user->department_id;
        $this->is_active = $user->is_active;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showEditModal = true;
    }

    public function createUser()
    {
        $this->authorize('create', User::class);
        
        $this->validate();

        // Check email uniqueness
        $this->validate([
            'email' => 'unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role' => $this->role,
            'department_id' => $this->department_id ?: null,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Benutzer erfolgreich erstellt.');
        $this->closeModal();
    }

    public function updateUser()
    {
        $this->authorize('update', $this->editingUser);
        
        $rules = $this->rules;
        $rules['email'] = 'required|string|email|max:255|unique:users,email,' . $this->editingUser->id;
        
        // Password is optional for updates
        if (empty($this->password)) {
            unset($rules['password']);
        }
        
        $this->validate($rules);

        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'department_id' => $this->department_id ?: null,
            'is_active' => $this->is_active,
        ];

        // Only update password if provided
        if (!empty($this->password)) {
            $updateData['password'] = bcrypt($this->password);
        }

        $this->editingUser->update($updateData);

        session()->flash('message', 'Benutzer erfolgreich aktualisiert.');
        $this->closeModal();
    }

    public function toggleUserStatus(User $user)
    {
        $this->authorize('update', $user);
        
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'aktiviert' : 'deaktiviert';
        session()->flash('message', "Benutzer {$user->name} wurde {$status}.");
    }

    public function deleteUser(User $user)
    {
        $this->authorize('delete', $user);
        
        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            session()->flash('error', 'Sie können sich nicht selbst löschen.');
            return;
        }

        $userName = $user->name;
        $user->delete();
        
        session()->flash('message', "Benutzer {$userName} wurde gelöscht.");
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->editingUser = null;
        $this->resetForm();
        $this->resetErrorBag();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'user';
        $this->department_id = '';
        $this->is_active = true;
    }

    public function render()
    {
        $query = User::with('department');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->where('role', $this->roleFilter);
        }

        if ($this->departmentFilter) {
            $query->where('department_id', $this->departmentFilter);
        }

        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter === '1');
        }

        $users = $query->orderBy('name')->paginate(15);
        $departments = Department::orderBy('name')->get();

        $roles = [
            'admin' => 'Administrator',
            'sterilization_staff' => 'Sterilisations-Personal',
            'or_staff' => 'OP-Personal',
            'purchasing_staff' => 'Einkauf',
            'user' => 'Benutzer',
        ];

        return view('livewire.users.users-index', compact('users', 'departments', 'roles'));
    }
}
