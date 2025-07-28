<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">App-Einstellungen</h1>
        <p class="mt-2 text-gray-600">Verwalte Dropdown-Optionen und Systemkonfigurationen</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex flex-wrap">
            <button 
                wire:click="setActiveTab('instrument-categories')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm {{ $activeTab === 'instrument-categories' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Instrument-Kategorien
            </button>
            <button 
                wire:click="setActiveTab('instrument-statuses')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'instrument-statuses' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Instrument-Status
            </button>
            <button 
                wire:click="setActiveTab('container-types')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'container-types' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Container-Arten
            </button>
            <button 
                wire:click="setActiveTab('container-statuses')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'container-statuses' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Container-Status
            </button>
            <button 
                wire:click="setActiveTab('defect-types')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'defect-types' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Defekt-Arten
            </button>
            <button 
                wire:click="setActiveTab('purchase-order-statuses')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'purchase-order-statuses' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Bestellstatus
            </button>
            <button 
                wire:click="setActiveTab('manufacturers')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'manufacturers' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Hersteller
            </button>
            <button 
                wire:click="setActiveTab('operating-rooms')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'operating-rooms' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                OP-Säle
            </button>
            <button 
                wire:click="setActiveTab('departments')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'departments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Abteilungen
            </button>
        </nav>
    </div>

    <!-- Content Area -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">{{ $this->getActiveTitle() }}</h2>
                @if(!in_array($activeTab, ['operating-rooms', 'departments']))
                    <button 
                        wire:click="openCreateModal"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Neu hinzufügen
                    </button>
                @endif
            </div>
        </div>

        <div class="px-6 py-4">
            @if(in_array($activeTab, ['operating-rooms', 'departments']))
                <!-- Database Model Tables -->
                @if($activeTab === 'operating-rooms')
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beschreibung</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($operatingRooms as $room)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $room->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $room->description ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $room->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $room->is_active ? 'Aktiv' : 'Inaktiv' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Keine OP-Säle gefunden</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @elseif($activeTab === 'departments')
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beschreibung</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($departments as $department)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $department->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $department->description ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-center text-gray-500">Keine Abteilungen gefunden</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <!-- Array-based Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($data as $item)
                        <div class="bg-gray-50 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <span class="text-sm font-medium text-gray-900">{{ $item->name }}</span>
                                @if($item->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ $item->description }}</p>
                                @endif
                                @if(isset($item->color))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1 bg-{{ $item->color }}-100 text-{{ $item->color }}-800">
                                        {{ $item->color }}
                                    </span>
                                @endif
                                @if(isset($item->severity))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1 
                                        {{ $item->severity === 'critical' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $item->severity === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $item->severity === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $item->severity === 'low' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst($item->severity) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <button 
                                    wire:click="openEditModal({{ $item->id }})"
                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                    Bearbeiten
                                </button>
                                <button 
                                    wire:click="openDeleteModal({{ $item->id }})"
                                    class="text-red-600 hover:text-red-800 text-sm">
                                    Löschen
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($data->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500">Keine Einträge gefunden. Fügen Sie Ihren ersten Eintrag hinzu.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 overflow-y-auto h-full w-full z-50 flex items-center justify-center" style="background-color: rgba(75, 85, 99, 0.5);">
            <div class="relative p-5 border w-80 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Neu hinzufügen: {{ $this->getActiveTitle() }}</h3>                    <div class="mb-4">
                        <label for="newValue" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input 
                            type="text" 
                            id="newValue"
                            wire:model="newValue"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Name eingeben...">
                        @error('newValue') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="newDescription" class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                        <textarea 
                            id="newDescription"
                            wire:model="newDescription"
                            rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Beschreibung eingeben..."></textarea>
                    </div>

                    @if(in_array($activeTab, ['instrument-statuses', 'container-statuses', 'purchase-order-statuses']))
                        <div class="mb-4">
                            <label for="newColor" class="block text-sm font-medium text-gray-700 mb-2">Farbe</label>
                            <select 
                                id="newColor"
                                wire:model="newColor"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="gray">Grau</option>
                                <option value="red">Rot</option>
                                <option value="yellow">Gelb</option>
                                <option value="green">Grün</option>
                                <option value="blue">Blau</option>
                                <option value="purple">Lila</option>
                                <option value="orange">Orange</option>
                            </select>
                        </div>
                    @endif

                    @if($activeTab === 'defect-types')
                        <div class="mb-4">
                            <label for="newSeverity" class="block text-sm font-medium text-gray-700 mb-2">Schweregrad</label>
                            <select 
                                id="newSeverity"
                                wire:model="newSeverity"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="low">Niedrig</option>
                                <option value="medium">Mittel</option>
                                <option value="high">Hoch</option>
                                <option value="critical">Kritisch</option>
                            </select>
                        </div>
                    @endif

                    <div class="flex justify-end space-x-3">
                        <button 
                            wire:click="resetModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Abbrechen
                        </button>
                        <button 
                            wire:click="create"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Hinzufügen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 overflow-y-auto h-full w-full z-50 flex items-center justify-center" style="background-color: rgba(75, 85, 99, 0.5);">
            <div class="relative p-5 border w-80 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Eintrag bearbeiten: {{ $this->getActiveTitle() }}</h3>
                    
                    <div class="mb-4">
                        <label for="editingValue" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input 
                            type="text" 
                            id="editingValue"
                            wire:model="editingValue"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('editingValue') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="editingDescription" class="block text-sm font-medium text-gray-700 mb-2">Beschreibung</label>
                        <textarea 
                            id="editingDescription"
                            wire:model="editingDescription"
                            rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    @if(in_array($activeTab, ['instrument-statuses', 'container-statuses', 'purchase-order-statuses']))
                        <div class="mb-4">
                            <label for="editingColor" class="block text-sm font-medium text-gray-700 mb-2">Farbe</label>
                            <select 
                                id="editingColor"
                                wire:model="editingColor"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="gray">Grau</option>
                                <option value="red">Rot</option>
                                <option value="yellow">Gelb</option>
                                <option value="green">Grün</option>
                                <option value="blue">Blau</option>
                                <option value="purple">Lila</option>
                                <option value="orange">Orange</option>
                            </select>
                        </div>
                    @endif

                    @if($activeTab === 'defect-types')
                        <div class="mb-4">
                            <label for="editingSeverity" class="block text-sm font-medium text-gray-700 mb-2">Schweregrad</label>
                            <select 
                                id="editingSeverity"
                                wire:model="editingSeverity"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="low">Niedrig</option>
                                <option value="medium">Mittel</option>
                                <option value="high">Hoch</option>
                                <option value="critical">Kritisch</option>
                            </select>
                        </div>
                    @endif

                    <div class="flex justify-end space-x-3">
                        <button 
                            wire:click="resetModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Abbrechen
                        </button>
                        <button 
                            wire:click="update"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Aktualisieren
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 overflow-y-auto h-full w-full z-50 flex items-center justify-center" style="background-color: rgba(75, 85, 99, 0.5);">
            <div class="relative p-5 border w-80 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Eintrag löschen</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Sind Sie sicher, dass Sie "{{ $deleteItem['value'] ?? '' }}" löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.
                    </p>

                    <div class="flex justify-center space-x-3">
                        <button 
                            wire:click="resetModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Abbrechen
                        </button>
                        <button 
                            wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Löschen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
