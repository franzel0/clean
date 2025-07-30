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
                <button 
                    wire:click="openCreateModal"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Neu hinzufügen
                </button>
            </div>
        </div>

        <div class="px-6 py-4">
            <!-- Database Model Tables -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beschreibung</th>
                            @if(in_array($activeTab, ['operating-rooms', 'departments']))
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Standort</th>
                            @endif
                            @if($activeTab === 'operating-rooms')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Abteilung</th>
                            @endif
                            @if(in_array($activeTab, ['instrument-statuses', 'container-statuses', 'purchase-order-statuses']))
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farbe</th>
                            @endif
                            @if($activeTab === 'defect-types')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schweregrad</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($this->getActiveData() as $item)
                            <tr class="{{ !$item->is_active ? 'bg-gray-50 opacity-75' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $item->is_active ? 'text-gray-900' : 'text-gray-500' }}">
                                    {{ $item->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->description ?? 'N/A' }}
                                </td>
                                @if(in_array($activeTab, ['operating-rooms', 'departments']))
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->code ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->location ?? 'N/A' }}
                                    </td>
                                @endif
                                @if($activeTab === 'operating-rooms')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->department->name ?? 'N/A' }}
                                    </td>
                                @endif
                                @if(in_array($activeTab, ['instrument-statuses', 'container-statuses', 'purchase-order-statuses']))
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $item->color }}-100 text-{{ $item->color }}-800">
                                            {{ ucfirst($item->color) }}
                                        </span>
                                    </td>
                                @endif
                                @if($activeTab === 'defect-types')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $item->severity === 'critical' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $item->severity === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                            {{ $item->severity === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $item->severity === 'low' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ ucfirst($item->severity) }}
                                        </span>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button 
                                        wire:click="toggleStatus({{ $item->id }})"
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full transition-colors duration-200 hover:opacity-80 {{ $item->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}"
                                        title="Klicken zum {{ $item->is_active ? 'Deaktivieren' : 'Aktivieren' }}">
                                        {{ $item->is_active ? 'Aktiv' : 'Inaktiv' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button 
                                        wire:click="openEditModal({{ $item->id }})"
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                        Bearbeiten
                                    </button>
                                    <button 
                                        wire:click="openDeleteModal({{ $item->id }})"
                                        class="text-red-600 hover:text-red-900">
                                        Löschen
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $activeTab === 'operating-rooms' ? '7' : ($activeTab === 'departments' ? '6' : ($activeTab === 'defect-types' ? '5' : (in_array($activeTab, ['instrument-statuses', 'container-statuses', 'purchase-order-statuses']) ? '5' : '4'))) }}" class="px-6 py-4 text-center text-gray-500">
                                    Keine Einträge gefunden. Fügen Sie Ihren ersten Eintrag hinzu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($data->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500">Keine Einträge gefunden. Fügen Sie Ihren ersten Eintrag hinzu.</p>
                </div>
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

                    @if(!in_array($activeTab, ['operating-rooms', 'departments']))
                        <div class="mb-4">
                            <div class="flex items-center mb-2">
                                <label for="newSortOrder" class="block text-sm font-medium text-gray-700">Sortierung</label>
                                <div class="ml-2 relative inline-block" x-data="{ showTooltip: false }">
                                    <button 
                                        type="button"
                                        @mouseenter="showTooltip = true" 
                                        @mouseleave="showTooltip = false"
                                        class="w-4 h-4 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-blue-600 transition-colors">
                                        i
                                    </button>
                                    <div 
                                        x-show="showTooltip" 
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg shadow-lg whitespace-nowrap z-50">
                                        <div class="text-center">
                                            <div>0 = automatisch zuweisen (nächste verfügbare Nummer)</div>
                                            <div>Zahl eingeben = manuelle Sortierung</div>
                                        </div>
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                    </div>
                                </div>
                            </div>
                            <input 
                                type="number" 
                                id="newSortOrder"
                                wire:model="newSortOrder"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0 = automatisch zuweisen..."
                                min="0">
                        </div>
                    @endif

                    @if(in_array($activeTab, ['operating-rooms', 'departments']))
                        <div class="mb-4">
                            <label for="newCode" class="block text-sm font-medium text-gray-700 mb-2">Code</label>
                            <input 
                                type="text" 
                                id="newCode"
                                wire:model="newCode"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Code eingeben...">
                        </div>

                        <div class="mb-4">
                            <label for="newLocation" class="block text-sm font-medium text-gray-700 mb-2">Standort</label>
                            <input 
                                type="text" 
                                id="newLocation"
                                wire:model="newLocation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Standort eingeben...">
                        </div>
                    @endif

                    @if($activeTab === 'operating-rooms')
                        <div class="mb-4">
                            <label for="newDepartmentId" class="block text-sm font-medium text-gray-700 mb-2">Abteilung</label>
                            <select 
                                id="newDepartmentId"
                                wire:model="newDepartmentId"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Abteilung auswählen...</option>
                                @foreach($this->getDepartments() as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if($activeTab === 'manufacturers')
                        <div class="mb-4">
                            <label for="newContactPerson" class="block text-sm font-medium text-gray-700 mb-2">Ansprechpartner</label>
                            <input 
                                type="text" 
                                id="newContactPerson"
                                wire:model="newContactPerson"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Name des Ansprechpartners...">
                        </div>

                        <div class="mb-4">
                            <label for="newContactEmail" class="block text-sm font-medium text-gray-700 mb-2">E-Mail</label>
                            <input 
                                type="email" 
                                id="newContactEmail"
                                wire:model="newContactEmail"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="email@beispiel.de">
                        </div>

                        <div class="mb-4">
                            <label for="newContactPhone" class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                            <input 
                                type="tel" 
                                id="newContactPhone"
                                wire:model="newContactPhone"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="+49 123 456789">
                        </div>

                        <div class="mb-4">
                            <label for="newWebsite" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input 
                                type="url" 
                                id="newWebsite"
                                wire:model="newWebsite"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="https://www.beispiel.de">
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

                    @if(!in_array($activeTab, ['operating-rooms', 'departments']))
                        <div class="mb-4">
                            <div class="flex items-center mb-2">
                                <label for="editingSortOrder" class="block text-sm font-medium text-gray-700">Sortierung</label>
                                <div class="ml-2 relative inline-block" x-data="{ showTooltip: false }">
                                    <button 
                                        type="button"
                                        @mouseenter="showTooltip = true" 
                                        @mouseleave="showTooltip = false"
                                        class="w-4 h-4 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-blue-600 transition-colors">
                                        i
                                    </button>
                                    <div 
                                        x-show="showTooltip" 
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg shadow-lg whitespace-nowrap z-50">
                                        <div class="text-center">
                                            <div>0 = automatisch zuweisen (nächste verfügbare Nummer)</div>
                                            <div>Zahl eingeben = manuelle Sortierung</div>
                                        </div>
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                    </div>
                                </div>
                            </div>
                            <input 
                                type="number" 
                                id="editingSortOrder"
                                wire:model="editingSortOrder"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                min="0">
                        </div>
                    @endif

                    @if($activeTab === 'manufacturers')
                        <div class="mb-4">
                            <label for="editingContactPerson" class="block text-sm font-medium text-gray-700 mb-2">Kontaktperson</label>
                            <input 
                                type="text" 
                                id="editingContactPerson"
                                wire:model="editingContactPerson"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="mb-4">
                            <label for="editingContactEmail" class="block text-sm font-medium text-gray-700 mb-2">E-Mail</label>
                            <input 
                                type="email" 
                                id="editingContactEmail"
                                wire:model="editingContactEmail"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="mb-4">
                            <label for="editingContactPhone" class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                            <input 
                                type="tel" 
                                id="editingContactPhone"
                                wire:model="editingContactPhone"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="mb-4">
                            <label for="editingWebsite" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input 
                                type="url" 
                                id="editingWebsite"
                                wire:model="editingWebsite"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    @endif

                    @if(in_array($activeTab, ['operating-rooms', 'departments']))
                        <div class="mb-4">
                            <label for="editingCode" class="block text-sm font-medium text-gray-700 mb-2">Code</label>
                            <input 
                                type="text" 
                                id="editingCode"
                                wire:model="editingCode"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="mb-4">
                            <label for="editingLocation" class="block text-sm font-medium text-gray-700 mb-2">Standort</label>
                            <input 
                                type="text" 
                                id="editingLocation"
                                wire:model="editingLocation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    @endif

                    @if($activeTab === 'operating-rooms')
                        <div class="mb-4">
                            <label for="editingDepartmentId" class="block text-sm font-medium text-gray-700 mb-2">Abteilung</label>
                            <select 
                                id="editingDepartmentId"
                                wire:model="editingDepartmentId"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Abteilung auswählen...</option>
                                @foreach($this->getDepartments() as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
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
