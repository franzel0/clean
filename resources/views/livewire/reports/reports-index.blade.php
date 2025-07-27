<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Berichte & Statistiken</h1>
            <p class="mt-1 text-sm text-gray-600">
                Übersicht über Instrumente, Defekte, Bestellungen und Bewegungen
            </p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zeitraum</label>
                    <select wire:model.live="selectedPeriod" 
                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="7">Letzte 7 Tage</option>
                        <option value="30">Letzte 30 Tage</option>
                        <option value="90">Letzte 90 Tage</option>
                        <option value="365">Letztes Jahr</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Abteilung</label>
                    <select wire:model.live="selectedDepartment" 
                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Alle Abteilungen</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Instruments -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tools text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Instrumente</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalInstruments) }}</p>
                        <p class="text-xs text-gray-500">{{ $activeInstruments }} aktiv</p>
                    </div>
                </div>
            </div>

            <!-- Containers -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Container</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalContainers) }}</p>
                        <p class="text-xs text-gray-500">{{ $activeContainers }} aktiv</p>
                    </div>
                </div>
            </div>

            <!-- Defect Reports -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Defektmeldungen</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalDefectReports) }}</p>
                        <p class="text-xs text-gray-500">{{ $openDefectReports }} offen</p>
                    </div>
                </div>
            </div>

            <!-- Movements -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-route text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Bewegungen</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalMovements) }}</p>
                        <p class="text-xs text-gray-500">{{ $recentMovements }} in {{ $selectedPeriod }} Tagen</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Instrument Status Distribution -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Instrument Status</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach(['available' => 'Verfügbar', 'in_use' => 'Im Einsatz', 'defective' => 'Defekt', 'in_repair' => 'In Reparatur', 'out_of_service' => 'Außer Betrieb'] as $status => $label)
                            @php
                                $count = $statusDistribution->get($status)?->count ?? 0;
                                $percentage = $totalInstruments > 0 ? ($count / $totalInstruments) * 100 : 0;
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded mr-3 
                                        @if($status === 'available') bg-green-500
                                        @elseif($status === 'in_use') bg-blue-500
                                        @elseif($status === 'defective') bg-red-500
                                        @elseif($status === 'in_repair') bg-yellow-500
                                        @else bg-gray-500
                                        @endif"></div>
                                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 mr-2">{{ $count }}</span>
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full 
                                            @if($status === 'available') bg-green-500
                                            @elseif($status === 'in_use') bg-blue-500
                                            @elseif($status === 'defective') bg-red-500
                                            @elseif($status === 'in_repair') bg-yellow-500
                                            @else bg-gray-500
                                            @endif" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Defect Type Distribution -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Defekttypen ({{ $selectedPeriod }} Tage)</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach(['broken' => 'Kaputt', 'dull' => 'Stumpf', 'bent' => 'Verbogen', 'missing_parts' => 'Teile fehlen', 'other' => 'Sonstiges'] as $type => $label)
                            @php
                                $count = $defectTypeDistribution->get($type)?->count ?? 0;
                                $percentage = $recentDefectReports > 0 ? ($count / $recentDefectReports) * 100 : 0;
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded mr-3 bg-red-400"></div>
                                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 mr-2">{{ $count }}</span>
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-red-400" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Top Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Defects -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Aktuelle Defekte</h3>
                </div>
                <div class="p-6">
                    @if($recentDefects->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentDefects as $defect)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-exclamation text-red-600 text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('instruments.show', $defect->instrument) }}" class="hover:text-blue-600">
                                                {{ $defect->instrument->name }}
                                            </a>
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $defect->reported_at->diffForHumans() }}</p>
                                        <p class="text-xs text-gray-600">{{ Str::limit($defect->description, 50) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('defect-reports.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                Alle Defektmeldungen anzeigen
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Keine aktuellen Defekte</p>
                    @endif
                </div>
            </div>

            <!-- Recent Movements -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Aktuelle Bewegungen</h3>
                </div>
                <div class="p-6">
                    @if($recentMovementsList->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentMovementsList as $movement)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                                            @if($movement->movement_type === 'dispatch') bg-blue-100
                                            @elseif($movement->movement_type === 'return') bg-green-100
                                            @elseif($movement->movement_type === 'transfer') bg-purple-100
                                            @elseif($movement->movement_type === 'sterilization') bg-cyan-100
                                            @elseif($movement->movement_type === 'repair') bg-orange-100
                                            @else bg-gray-100
                                            @endif">
                                            <i class="fas fa-arrow-right text-xs
                                                @if($movement->movement_type === 'dispatch') text-blue-600
                                                @elseif($movement->movement_type === 'return') text-green-600
                                                @elseif($movement->movement_type === 'transfer') text-purple-600
                                                @elseif($movement->movement_type === 'sterilization') text-cyan-600
                                                @elseif($movement->movement_type === 'repair') text-orange-600
                                                @else text-gray-600
                                                @endif"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('instruments.show', $movement->instrument) }}" class="hover:text-blue-600">
                                                {{ $movement->instrument->name }}
                                            </a>
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $movement->moved_at->diffForHumans() }}</p>
                                        <p class="text-xs text-gray-600">{{ $movement->movement_type_display }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('movements.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                Alle Bewegungen anzeigen
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Keine aktuellen Bewegungen</p>
                    @endif
                </div>
            </div>

            <!-- Top Defective Instruments -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Häufigste Defekte</h3>
                </div>
                <div class="p-6">
                    @if($topDefectiveInstruments->count() > 0)
                        <div class="space-y-4">
                            @foreach($topDefectiveInstruments as $instrument)
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('instruments.show', $instrument) }}" class="hover:text-blue-600">
                                                {{ $instrument->name }}
                                            </a>
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $instrument->serial_number }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-300">
                                            {{ $instrument->defect_reports_count }} Defekte
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Keine Defekte im Zeitraum</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
