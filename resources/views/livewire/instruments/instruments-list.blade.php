<div class="container mx-auto px-4 py-8">
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.instruments') }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $instruments->total() }} {{ __('messages.instruments_found') }}</p>
        </div>
        <div class="flex space-x-3">
            <button wire:click="createInstrument" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                {{ __('messages.create_instrument') }}
            </button>
            <a href="{{ route('defect-reports.create') }}" 
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                {{ __('messages.create_defect_report') }}
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="dashboard-card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <input type="text" 
                       wire:model.live="search" 
                       placeholder="{{ __('messages.search') }}..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <select wire:model.live="statusFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('messages.all_status') }}</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <select wire:model.live="categoryFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('messages.all_categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="containerFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('messages.all_containers') }}</option>
                    @foreach($containers as $container)
                        <option value="{{ $container->id }}">{{ $container->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" 
                           wire:model.live="activeOnly" 
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <span class="text-sm text-gray-700 font-medium">Nur aktive</span>
                </label>
            </div>

            <div class="flex space-x-2">
                <button wire:click="resetFilters" 
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                    Filter zurücksetzen
                </button>
            </div>
        </div>
    </div>

    <!-- Instrumente Tabelle -->
    <div class="dashboard-card">
        @if($instruments->count() > 0)
            <div class="overflow-x-auto" style="min-height: calc(100vh - 30rem);">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 transition {{ $sortBy === 'name' ? 'bg-blue-50' : '' }}" wire:click="sort('name')">
                                {{ __('messages.instrument') }}
                                @if($sortBy === 'name')
                                    <span class="inline-block ml-2 text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 transition {{ $sortBy === 'serial_number' ? 'bg-blue-50' : '' }}" wire:click="sort('serial_number')">
                                {{ __('messages.serial_number') }}
                                @if($sortBy === 'serial_number')
                                    <span class="inline-block ml-2 text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 transition {{ $sortBy === 'category_id' ? 'bg-blue-50' : '' }}" wire:click="sort('category_id')">
                                {{ __('messages.category') }}
                                @if($sortBy === 'category_id')
                                    <span class="inline-block ml-2 text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 transition {{ $sortBy === 'status_id' ? 'bg-blue-50' : '' }}" wire:click="sort('status_id')">
                                {{ __('messages.status') }}
                                @if($sortBy === 'status_id')
                                    <span class="inline-block ml-2 text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 transition {{ $sortBy === 'current_container_id' ? 'bg-blue-50' : '' }}" wire:click="sort('current_container_id')">
                                {{ __('messages.containers') }}
                                @if($sortBy === 'current_container_id')
                                    <span class="inline-block ml-2 text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 transition {{ $sortBy === 'current_location_id' ? 'bg-blue-50' : '' }}" wire:click="sort('current_location_id')">
                                {{ __('messages.current_location') }}
                                @if($sortBy === 'current_location_id')
                                    <span class="inline-block ml-2 text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($instruments as $instrument)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">{{ $instrument->name }}</div>
                                    @if($instrument->manufacturerRelation)
                                        <div class="text-sm text-gray-600">{{ $instrument->manufacturerRelation->name }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-mono text-sm text-gray-900">{{ $instrument->serial_number }}</td>
                                <td class="py-3 px-4 text-gray-900">{{ $instrument->category_display }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $instrument->instrumentStatus?->bg_class ?? 'bg-gray-100' }} {{ $instrument->instrumentStatus?->text_class ?? 'text-gray-800' }}">
                                        {{ $instrument->status_display }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-900">
                                    {{ $instrument->currentContainer?->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-gray-900">
                                    {{ $instrument->currentLocation?->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="relative inline-block text-left" 
                                         x-data="{ open: false }" 
                                         :style="{ 'z-index': open ? '9999' : '999' }"
                                         @click.away="open = false">
                                        <div>
                                            <button @click="open = !open" 
                                                    class="inline-flex justify-center w-full rounded-md border-2 border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                                    type="button" 
                                                    aria-expanded="true" 
                                                    aria-haspopup="true">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                </svg>
                                                Aktionen
                                                <svg class="-mr-1 ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                             style="z-index: 99999;"
                                             style="z-index: 9999;"
                                             role="menu" 
                                             aria-orientation="vertical">
                                            <div class="py-1" role="none">
                                                <a href="{{ route('instruments.show', $instrument->id) }}" 
                                                   class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 transition-colors duration-150"
                                                   role="menuitem">
                                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Anzeigen
                                                </a>
                                                
                                                <button wire:click="editInstrument({{ $instrument->id }})" 
                                                        class="group flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors duration-150"
                                                        role="menuitem">
                                                    <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Bearbeiten
                                                </button>
                                                
                                                @if($instrument->instrumentStatus?->name !== 'Außer Betrieb')
                                                    <div class="border-t border-gray-100"></div>
                                                    <a href="{{ route('defect-reports.create', ['instrument' => $instrument->id]) }}" 
                                                       class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-800 transition-colors duration-150"
                                                       role="menuitem">
                                                        <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                        </svg>
                                                        Defekt melden
                                                    </a>
                                                @endif
                                                
                                                <div class="border-t border-gray-100"></div>
                                                <button wire:click="deleteInstrument({{ $instrument->id }})" 
                                                        onclick="return confirm('Sind Sie sicher? Wenn das Instrument bereits verwendet wurde (Defektmeldungen, Bewegungen), wird es deaktiviert statt gelöscht.')"
                                                        class="group flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-800 transition-colors duration-150"
                                                        role="menuitem">
                                                    <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Löschen
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Zeige <strong>{{ ($instruments->currentPage() - 1) * $instruments->perPage() + 1 }}</strong> bis 
                        <strong>{{ min($instruments->currentPage() * $instruments->perPage(), $instruments->total()) }}</strong> von 
                        <strong>{{ $instruments->total() }}</strong> Instrumenten
                    </p>
                    
                    <div class="flex items-center space-x-2">
                        @if ($instruments->onFirstPage())
                            <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-200 rounded-md cursor-not-allowed">
                                ← Zurück
                            </span>
                        @else
                            <button wire:click="previousPage" 
                                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                ← Zurück
                            </button>
                        @endif
                        
                        <div class="flex items-center space-x-1">
                            @for ($i = 1; $i <= $instruments->lastPage(); $i++)
                                @if ($i === $instruments->currentPage())
                                    <span class="px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-md">
                                        {{ $i }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $i }})" 
                                            class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        {{ $i }}
                                    </button>
                                @endif
                            @endfor
                        </div>
                        
                        @if ($instruments->hasMorePages())
                            <button wire:click="nextPage" 
                                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Weiter →
                            </button>
                        @else
                            <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-200 rounded-md cursor-not-allowed">
                                Weiter →
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">Keine Instrumente gefunden.</p>
            </div>
        @endif
    </div>
</div>
