<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Benutzerverwaltung</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Verwalten Sie Benutzerkonten und Berechtigungen
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button wire:click="openCreateModal" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Neuer Benutzer
                    </button>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('message') }}
            </div>
        @endif

        @if(session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Suchen</label>
                    <input type="text" 
                           wire:model.live="search" 
                           placeholder="Name oder E-Mail..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rolle</label>
                    <select wire:model.live="roleFilter" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Alle Rollen</option>
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Abteilung</label>
                    <select wire:model.live="departmentFilter" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Alle Abteilungen</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model.live="statusFilter" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Alle</option>
                        <option value="1">Aktiv</option>
                        <option value="0">Inaktiv</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    {{ $users->total() }} Benutzer gefunden
                </div>
                <button wire:click="resetFilters" 
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                    Filter zurücksetzen
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Benutzer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rolle
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Abteilung
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Erstellt
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aktionen
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium text-sm">
                                                {{ $user->initials() }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        @if($user->role === 'admin') bg-red-100 text-red-800
                                        @elseif($user->role === 'sterilization_staff') bg-blue-100 text-blue-800
                                        @elseif($user->role === 'or_staff') bg-green-100 text-green-800
                                        @elseif($user->role === 'purchasing_staff') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $user->role_display }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user->department?->name ?? 'Keine Abteilung' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        @if($user->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                        @if($user->is_active) Aktiv @else Inaktiv @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('d.m.Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                    @if(env("APP_DEMO") == false)
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <div>
                                            <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="options-menu" aria-haspopup="true" aria-expanded="true">
                                                Aktionen
                                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div x-show="open" 
                                             @click.away="open = false"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                                             style="display: none;">
                                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                <a href="#" wire:click.prevent="openEditModal({{ $user->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Bearbeiten</a>
                                                <a href="#" wire:click.prevent="toggleUserStatus({{ $user->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                    @if($user->is_active) Deaktivieren @else Aktivieren @endif
                                                </a>
                                                <a href="#" wire:click.prevent="openEmailModal({{ $user->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">E-Mail senden</a>
                                                @if($user->id !== auth()->id())
                                                    <a href="#" wire:click.prevent="deleteUser({{ $user->id }})" wire:confirm="Sind Sie sicher, dass Sie diesen Benutzer löschen möchten?" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 hover:text-red-800" role="menuitem">Löschen</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                        <span class="text-gray-500">Im Demo Modus deaktiviert.</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    Keine Benutzer gefunden.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showCreateModal || $showEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" wire:ignore.self>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0" style="background-color: rgba(75, 85, 99, 0.5);" wire:click="closeModal"></div>
            
            <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl max-w-lg w-full">
                <form wire:submit.prevent="{{ $showCreateModal ? 'createUser' : 'updateUser' }}">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            @if($showCreateModal) Neuen Benutzer erstellen @else Benutzer bearbeiten @endif
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" 
                                       wire:model="name"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       required>
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">E-Mail *</label>
                                <input type="email" 
                                       wire:model="email"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       required>
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Passwort @if($showCreateModal) * @else (leer lassen um nicht zu ändern) @endif
                                </label>
                                <input type="password" 
                                       wire:model="password"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       @if($showCreateModal) required @endif>
                                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Passwort bestätigen</label>
                                <input type="password" 
                                       wire:model="password_confirmation"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rolle *</label>
                                <select wire:model="role" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    @foreach($roles as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Abteilung</label>
                                <select wire:model="department_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Keine Abteilung</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" 
                                       wire:model="is_active"
                                       id="is_active"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Benutzer ist aktiv
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button type="button" 
                                wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg text-sm font-medium">
                            Abbrechen
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                            @if($showCreateModal) Erstellen @else Speichern @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Email Modal -->
    @if($showEmailModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" wire:ignore.self>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0" style="background-color: rgba(75, 85, 99, 0.5);" wire:click="closeEmailModal"></div>
            
            <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl max-w-lg w-full">
                <form wire:submit.prevent="sendEmail">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            E-Mail an Benutzer senden
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Betreff</label>
                                <input type="text" 
                                       wire:model="emailSubject"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nachricht</label>
                                <textarea wire:model="emailBody" 
                                          rows="4" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                          required></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button type="button" 
                                wire:click="closeEmailModal"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg text-sm font-medium">
                            Abbrechen
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                            Senden
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
