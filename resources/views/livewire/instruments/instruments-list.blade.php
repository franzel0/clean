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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
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
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Instrument</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Seriennummer</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Kategorie</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Status</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Container</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Standort</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Aktionen</th>
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
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        @if($instrument->instrumentStatus?->color === 'green') bg-green-100 text-green-800
                                        @elseif($instrument->instrumentStatus?->color === 'blue') bg-blue-100 text-blue-800
                                        @elseif($instrument->instrumentStatus?->color === 'red') bg-red-100 text-red-800
                                        @elseif($instrument->instrumentStatus?->color === 'yellow') bg-yellow-100 text-yellow-800
                                        @elseif($instrument->instrumentStatus?->color === 'purple') bg-purple-100 text-purple-800
                                        @elseif($instrument->instrumentStatus?->color === 'orange') bg-orange-100 text-orange-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
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
                                    <div class="flex space-x-2">
                                        <a href="{{ route('instruments.show', $instrument->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            Anzeigen
                                        </a>
                                        
                                        <button wire:click="editInstrument({{ $instrument->id }})" 
                                                class="text-green-600 hover:text-green-800 text-sm">
                                            Bearbeiten
                                        </button>
                                        
                                        <button wire:click="deleteInstrument({{ $instrument->id }})" 
                                                onclick="return confirm('Sind Sie sicher, dass Sie dieses Instrument löschen möchten?')"
                                                class="text-red-600 hover:text-red-800 text-sm">
                                            Löschen
                                        </button>
                                        
                                        @if($instrument->status !== 'defective')
                                            <a href="{{ route('defect-reports.create', ['instrument' => $instrument->id]) }}" 
                                               class="text-orange-600 hover:text-orange-800 text-sm">
                                                Defekt melden
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $instruments->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">Keine Instrumente gefunden.</p>
            </div>
        @endif
    </div>
</div>
