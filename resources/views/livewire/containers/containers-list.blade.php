<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Container</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $containers->total() }} Container gefunden</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('containers.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium border-2 border-blue-700 shadow-lg hover:shadow-xl transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>Neuer Container
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <input type="text" 
                       wire:model.live="search" 
                       placeholder="Container suchen..." 
                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <select wire:model.live="typeFilter" 
                        class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alle Typen</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}">
                            @switch($type)
                                @case('surgical_set') Chirurgisches Set @break
                                @case('basic_set') Basis Set @break
                                @case('special_set') Spezial Set @break
                                @default {{ $type }}
                            @endswitch
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex space-x-2">
                <button wire:click="resetFilters" 
                        class="px-3 py-2 border-2 border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 hover:border-gray-400">
                    Filter zurücksetzen
                </button>
            </div>
        </div>
    </div>

    <!-- Container Table -->
    <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 overflow-hidden">
        @if($containers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y-2 divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                Typ
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                Aktiv
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                Status
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                Instrumente
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                Nicht verfügbar
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Aktionen
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($containers as $container)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $container->name }}</div>
                                        <div class="text-xs text-gray-500 font-mono">{{ $container->barcode }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300">
                                        @switch($container->type)
                                            @case('surgical_set') Chirurgisch @break
                                            @case('basic_set') Basis @break
                                            @case('special_set') Spezial @break
                                            @default {{ $container->type }}
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center border-r border-gray-200">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold border
                                        @if($container->is_active) bg-green-100 text-green-800 border-green-300
                                        @else bg-red-100 text-red-800 border-red-300
                                        @endif">
                                        @if($container->is_active) Ja @else Nein @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center border-r border-gray-200">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold border
                                        @switch($container->status)
                                            @case('complete') bg-green-100 text-green-800 border-green-300 @break
                                            @case('incomplete') bg-yellow-100 text-yellow-800 border-yellow-300 @break
                                            @case('out_of_service') bg-red-100 text-red-800 border-red-300 @break
                                            @default bg-gray-100 text-gray-800 border-gray-300
                                        @endswitch">
                                        @switch($container->status)
                                            @case('complete') Vollständig @break
                                            @case('incomplete') Unvollständig @break
                                            @case('out_of_service') Außer Betrieb @break
                                            @default {{ $container->status }}
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center border-r border-gray-200">
                                    <div class="text-sm font-bold text-gray-900">{{ $container->instruments->count() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center border-r border-gray-200">
                                    @if($container->unavailable_instruments_count > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300">
                                            {{ $container->unavailable_instruments_count }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('containers.show', $container) }}"
                                           class="text-blue-600 hover:text-blue-800 text-xs font-bold border border-blue-300 hover:border-blue-400 hover:bg-blue-50 rounded px-2 py-1 transition-all duration-200">
                                            Anzeigen
                                        </a>
                                        <a href="{{ route('containers.edit', $container) }}"
                                           class="text-orange-600 hover:text-orange-800 text-xs font-bold border border-orange-300 hover:border-orange-400 hover:bg-orange-50 rounded px-2 py-1 transition-all duration-200">
                                            Bearbeiten
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t-2 border-gray-200 bg-gray-50">
                {{ $containers->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Keine Container gefunden</h3>
                <p class="text-gray-600 mb-6">Es wurden keine Container gefunden, die Ihren Suchkriterien entsprechen.</p>
                <a href="{{ route('containers.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-bold border-2 border-blue-700 shadow-lg hover:shadow-xl transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>ERSTEN CONTAINER ERSTELLEN
                </a>
            </div>
        @endif
    </div>
</div>
