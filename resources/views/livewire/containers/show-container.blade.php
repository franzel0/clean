<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $container->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Container-Details und enthaltene Instrumente
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('containers.edit', $container) }}" 
                       class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 border-2 border-orange-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Bearbeiten
                    </a>
                    <a href="{{ route('containers.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 border-2 border-gray-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Zurück zur Liste
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Status & Information</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border-2 outline outline-2 outline-offset-2
                            @if($container->is_active) bg-green-100 text-green-800 border-green-300 outline-green-400
                            @else bg-red-100 text-red-800 border-red-300 outline-red-400
                            @endif">
                            @if($container->is_active) AKTIV @else INAKTIV @endif
                        </span>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-orange-100 text-orange-800 border-2 border-orange-300 outline outline-2 outline-offset-2 outline-orange-400">
                            <p class="text-sm">Status:&nbsp;</p>
                            {{ $container->containerStatus?->name ?? 'Unbekannt' }}
                        </span>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-blue-100 text-blue-800 border-2 border-blue-300 outline outline-2 outline-offset-2 outline-blue-400">
                            <p class="text-sm">Typ:&nbsp;</p>
                            {{ $container->containerType?->name ?? 'Unbekannt' }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg border-2 border-gray-200">
                            <h3 class="text-sm font-bold text-gray-700 mb-2">Barcode</h3>
                            <p class="text-lg font-mono font-bold text-gray-900">{{ $container->barcode }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border-2 border-gray-200">
                            <h3 class="text-sm font-bold text-gray-700 mb-2">Anzahl Instrumente</h3>
                            <p class="text-lg font-bold text-blue-600">{{ $container->instruments->count() }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border-2 border-gray-200">
                            <h3 class="text-sm font-bold text-gray-700 mb-2">Erstellt am</h3>
                            <p class="text-lg font-bold text-gray-900">{{ $container->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border-2 border-gray-200">
                            <h3 class="text-sm font-bold text-gray-700 mb-2">Letztes Update</h3>
                            <p class="text-lg font-bold text-gray-900">{{ $container->updated_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>

                    @if($container->description)
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg border-2 border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 mb-2">Beschreibung</h3>
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $container->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Instruments -->
            @if($container->instruments->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Enthaltene Instrumente ({{ $container->instruments->count() }})</h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('instruments.create', ['container' => $container->id]) }}" 
                           class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-bold transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Neu erstellen
                        </a>
                        <button wire:click="openAssignModal" 
                                class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-bold transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Zuweisen
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($container->instruments as $instrument)
                        <div class="border-2 border-gray-300 rounded-lg p-4 hover:shadow-md hover:border-gray-400 transition-all duration-200">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900 mb-1">{{ $instrument->name }}</h3>
                                    <p class="text-sm text-gray-600 font-mono bg-gray-100 px-2 py-1 rounded">{{ $instrument->serial_number }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold border
                                    @if($instrument->instrumentStatus?->name && stripos($instrument->instrumentStatus->name, 'verfügbar') !== false) bg-green-100 text-green-800 border-green-300
                                    @elseif($instrument->instrumentStatus?->name && (stripos($instrument->instrumentStatus->name, 'benutzung') !== false || stripos($instrument->instrumentStatus->name, 'einsatz') !== false)) bg-blue-100 text-blue-800 border-blue-300
                                    @elseif($instrument->instrumentStatus?->name && (stripos($instrument->instrumentStatus->name, 'defekt') !== false || stripos($instrument->instrumentStatus->name, 'außer betrieb') !== false)) bg-red-100 text-red-800 border-red-300
                                    @elseif($instrument->instrumentStatus?->name && stripos($instrument->instrumentStatus->name, 'wartung') !== false) bg-yellow-100 text-yellow-800 border-yellow-300
                                    @else bg-gray-100 text-gray-800 border-gray-300
                                    @endif">
                                    {{ $instrument->instrumentStatus?->name ?? 'Unbekannt' }}
                                </span>
                            </div>

                            <div class="space-y-2">
                                <div class="text-xs">
                                    <span class="font-bold text-gray-700">Kategorie:</span>
                                    <span class="text-gray-600">{{ $instrument->category?->name ?? 'Unbekannt' }}</span>
                                </div>
                                @if($instrument->manufacturerRelation?->name)
                                <div class="text-xs">
                                    <span class="font-bold text-gray-700">Hersteller:</span>
                                    <span class="text-gray-600">{{ $instrument->manufacturerRelation->name }}</span>
                                </div>
                                @endif
                                @if($instrument->defectReports?->count() > 0)
                                <div class="text-xs">
                                    <span class="font-bold text-red-700">Defektmeldungen:</span>
                                    <span class="text-red-600 font-bold">{{ $instrument->defectReports->count() }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <a href="{{ route('instruments.show', $instrument) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-bold">
                                    Details anzeigen →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Enthaltene Instrumente</h2>
                </div>
                <div class="p-6">
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Keine Instrumente</h3>
                        <p class="text-gray-600 mb-4">Dieser Container enthält derzeit keine Instrumente.</p>
                        <div class="space-x-3">
                            <a href="{{ route('instruments.create', ['container' => $container->id]) }}" 
                               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Neues Instrument erstellen
                            </a>
                            <button wire:click="openAssignModal" 
                               class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Vorhandenes Instrument zuweisen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Statistiken</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $availableCount = $container->instruments->filter(function($instrument) {
                                return $instrument->instrumentStatus && stripos($instrument->instrumentStatus->name, 'verfügbar') !== false;
                            })->count();
                            
                            $inUseCount = $container->instruments->filter(function($instrument) {
                                return $instrument->instrumentStatus && (stripos($instrument->instrumentStatus->name, 'benutzung') !== false || stripos($instrument->instrumentStatus->name, 'einsatz') !== false);
                            })->count();
                            
                            $defectiveCount = $container->instruments->filter(function($instrument) {
                                return $instrument->instrumentStatus && (stripos($instrument->instrumentStatus->name, 'defekt') !== false || stripos($instrument->instrumentStatus->name, 'außer betrieb') !== false);
                            })->count();
                            
                            $repairCount = $container->instruments->filter(function($instrument) {
                                return $instrument->instrumentStatus && stripos($instrument->instrumentStatus->name, 'wartung') !== false;
                            })->count();
                        @endphp
                        
                        <div class="text-center bg-green-50 p-4 rounded-lg border-2 border-green-200">
                            <div class="text-2xl font-bold text-green-600">{{ $availableCount }}</div>
                            <div class="text-sm font-bold text-green-800">VERFÜGBAR</div>
                        </div>
                        
                        <div class="text-center bg-blue-50 p-4 rounded-lg border-2 border-blue-200">
                            <div class="text-2xl font-bold text-blue-600">{{ $inUseCount }}</div>
                            <div class="text-sm font-bold text-blue-800">IM EINSATZ</div>
                        </div>
                        
                        <div class="text-center bg-red-50 p-4 rounded-lg border-2 border-red-200">
                            <div class="text-2xl font-bold text-red-600">{{ $defectiveCount }}</div>
                            <div class="text-sm font-bold text-red-800">DEFEKT</div>
                        </div>
                        
                        <div class="text-center bg-yellow-50 p-4 rounded-lg border-2 border-yellow-200">
                            <div class="text-2xl font-bold text-yellow-600">{{ $repairCount }}</div>
                            <div class="text-sm font-bold text-yellow-800">REPARATUR</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            @if($container->instruments->flatMap->defectReports->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Letzte Aktivitäten</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($container->instruments->flatMap->defectReports->sortByDesc('created_at')->take(5) as $report)
                        <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <a href="{{ route('defect-reports.show', $report) }}" 
                                           class="font-bold text-blue-600 hover:text-blue-800">
                                            {{ $report->report_number }}
                                        </a>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold border
                                            @switch($report->status)
                                                @case('reported') bg-yellow-100 text-yellow-800 border-yellow-300 @break
                                                @case('acknowledged') bg-blue-100 text-blue-800 border-blue-300 @break
                                                @case('in_review') bg-purple-100 text-purple-800 border-purple-300 @break
                                                @case('ordered') bg-indigo-100 text-indigo-800 border-indigo-300 @break
                                                @case('received') bg-green-100 text-green-800 border-green-300 @break
                                                @case('repaired') bg-green-100 text-green-800 border-green-300 @break
                                                @case('closed') bg-gray-100 text-gray-800 border-gray-300 @break
                                                @default bg-gray-100 text-gray-800 border-gray-300
                                            @endswitch">
                                            {{ $report->status_display }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-2">
                                        <span class="font-bold">{{ $report->instrument->name }}</span> - {{ $report->description }}
                                    </p>
                                    <div class="text-xs text-gray-600">
                                        {{ $report->reported_at->format('d.m.Y H:i') }} von {{ $report->reportedBy->name }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold border
                                        @switch($report->severity)
                                            @case('low') bg-green-100 text-green-800 border-green-300 @break
                                            @case('medium') bg-yellow-100 text-yellow-800 border-yellow-300 @break
                                            @case('high') bg-orange-100 text-orange-800 border-orange-300 @break
                                            @case('critical') bg-red-100 text-red-800 border-red-300 @break
                                        @endswitch">
                                        {{ $report->severity_display }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Assign Instrument Modal -->
    @if($showAssignModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Background overlay -->
        <div class="fixed inset-0" style="background-color: rgba(75, 85, 99, 0.5);" wire:click="closeAssignModal"></div>
        
        <!-- Modal container -->
        <div class="flex min-h-full items-center justify-center p-4">
            <!-- Modal panel -->
            <div class="relative w-full max-w-4xl bg-white rounded-lg shadow-xl">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">
                            Instrument zu Container hinzufügen
                        </h3>
                        <button wire:click="closeAssignModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    @if(count($availableInstruments) > 0)

                        <div class="mb-4 flex flex-col gap-2">
                            <p class="text-sm text-gray-600">
                                Wählen Sie ein verfügbares Instrument aus, um es zu diesem Container hinzuzufügen:
                            </p>
                            <input type="text"
                                   wire:model.live="instrumentFilter"
                                   placeholder="Instrumente filtern... (Name, Seriennummer, Kategorie, Hersteller)"
                                   class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm" />
                        </div>

                        <div class="max-h-96 overflow-y-auto">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($this->filteredInstruments as $instrument)
                                <div class="border-2 border-gray-300 rounded-lg p-4 hover:border-blue-400 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 mb-1">{{ $instrument->name }}</h4>
                                            <p class="text-sm text-gray-600 font-mono bg-gray-100 px-2 py-1 rounded">{{ $instrument->serial_number }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold border
                                            @if($instrument->instrumentStatus && stripos($instrument->instrumentStatus->name, 'verfügbar') !== false) bg-green-100 text-green-800 border-green-300
                                            @elseif($instrument->instrumentStatus && (stripos($instrument->instrumentStatus->name, 'benutzung') !== false || stripos($instrument->instrumentStatus->name, 'einsatz') !== false)) bg-blue-100 text-blue-800 border-blue-300
                                            @elseif($instrument->instrumentStatus && (stripos($instrument->instrumentStatus->name, 'defekt') !== false || stripos($instrument->instrumentStatus->name, 'außer betrieb') !== false)) bg-red-100 text-red-800 border-red-300
                                            @elseif($instrument->instrumentStatus && stripos($instrument->instrumentStatus->name, 'wartung') !== false) bg-yellow-100 text-yellow-800 border-yellow-300
                                            @else bg-gray-100 text-gray-800 border-gray-300
                                            @endif">
                                            {{ $instrument->status_display }}
                                        </span>
                                    </div>

                                    <div class="space-y-1 mb-3">
                                        <div class="text-xs">
                                            <span class="font-bold text-gray-700">Kategorie:</span>
                                            <span class="text-gray-600">{{ $instrument->category_display }}</span>
                                        </div>
                                        @if($instrument->manufacturerRelation)
                                        <div class="text-xs">
                                            <span class="font-bold text-gray-700">Hersteller:</span>
                                            <span class="text-gray-600">{{ $instrument->manufacturerRelation->name }}</span>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="pt-3 border-t border-gray-200">
                                        <button wire:click="assignInstrument({{ $instrument->id }})" 
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-bold transition-colors duration-200">
                                            Zu Container hinzufügen
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Keine verfügbaren Instrumente</h4>
                            <p class="text-gray-600 mb-4">
                                Alle Instrumente sind bereits anderen Containern zugewiesen oder inaktiv.
                            </p>
                            <a href="{{ route('instruments.create', ['container' => $container->id]) }}" 
                               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Neues Instrument erstellen
                            </a>
                        </div>
                    @endif

                    <div class="mt-6 flex justify-end">
                        <button wire:click="closeAssignModal" 
                                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium">
                            Schließen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
