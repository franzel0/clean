<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $instrument->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Instrumentendetails und Verlauf
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('instruments.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Status</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border-2 outline outline-2 outline-offset-2
                            @switch($instrument->status)
                                @case('available') bg-green-100 text-green-800 border-green-300 outline-green-400 @break
                                @case('in_use') bg-blue-100 text-blue-800 border-blue-300 outline-blue-400 @break
                                @case('defective') bg-red-100 text-red-800 border-red-300 outline-red-400 @break
                                @case('in_repair') bg-yellow-100 text-yellow-800 border-yellow-300 outline-yellow-400 @break
                                @case('out_of_service') bg-gray-100 text-gray-800 border-gray-300 outline-gray-400 @break
                                @default bg-gray-100 text-gray-800 border-gray-300 outline-gray-400
                            @endswitch">
                            {{ $instrument->status_display }}
                        </span>
                        @if($instrument->status !== 'defective')
                            <a href="{{ route('defect-reports.create', ['instrument' => $instrument->id]) }}" 
                               class="ml-4 inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                                Defekt melden
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Instrument Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Instrumentendetails</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Name</h3>
                            <p class="text-gray-900">{{ $instrument->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Seriennummer</h3>
                            <p class="text-gray-900 font-mono">{{ $instrument->serial_number }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Kategorie</h3>
                            <p class="text-gray-900">{{ $instrument->category_display }}</p>
                        </div>
                        @if($instrument->manufacturer)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Hersteller</h3>
                            <p class="text-gray-900">{{ $instrument->manufacturer }}</p>
                        </div>
                        @endif
                        @if($instrument->model)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Modell</h3>
                            <p class="text-gray-900">{{ $instrument->model }}</p>
                        </div>
                        @endif
                        @if($instrument->purchase_date)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Kaufdatum</h3>
                            <p class="text-gray-900">{{ $instrument->purchase_date->format('d.m.Y') }}</p>
                        </div>
                        @endif
                        @if($instrument->warranty_until)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Garantie bis</h3>
                            <p class="text-gray-900">{{ $instrument->warranty_until->format('d.m.Y') }}</p>
                        </div>
                        @endif
                        @if($instrument->last_maintenance)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Letzte Wartung</h3>
                            <p class="text-gray-900">{{ $instrument->last_maintenance->format('d.m.Y') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($instrument->currentContainer)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Aktueller Container</h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-blue-900">{{ $instrument->currentContainer->name }}</p>
                                    <p class="text-sm text-blue-700">{{ $instrument->currentContainer->barcode }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($instrument->notes)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Notizen</h3>
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $instrument->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Bewegungshistorie -->
            @if($instrument->movements->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-route text-blue-600 mr-2"></i>
                        Bewegungshistorie ({{ $instrument->movements->count() }})
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Verlauf aller Bewegungen und Statusänderungen</p>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($instrument->movements->sortByDesc('performed_at')->take(10) as $movement)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full 
                                                @if($movement->movement_type === 'dispatch') bg-blue-500 
                                                @elseif($movement->movement_type === 'return') bg-green-500
                                                @elseif($movement->movement_type === 'transfer') bg-purple-500
                                                @elseif($movement->movement_type === 'sterilization') bg-cyan-500
                                                @elseif($movement->movement_type === 'repair') bg-orange-500
                                                @else bg-gray-500 
                                                @endif 
                                                flex items-center justify-center ring-8 ring-white">
                                                @if($movement->movement_type === 'dispatch')
                                                    <i class="fas fa-arrow-right text-white text-xs"></i>
                                                @elseif($movement->movement_type === 'return')
                                                    <i class="fas fa-arrow-left text-white text-xs"></i>
                                                @elseif($movement->movement_type === 'transfer')
                                                    <i class="fas fa-exchange-alt text-white text-xs"></i>
                                                @elseif($movement->movement_type === 'sterilization')
                                                    <i class="fas fa-shield-alt text-white text-xs"></i>
                                                @elseif($movement->movement_type === 'repair')
                                                    <i class="fas fa-wrench text-white text-xs"></i>
                                                @else
                                                    <i class="fas fa-circle text-white text-xs"></i>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $movement->movement_type_display }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $movement->performed_at->format('d.m.Y H:i') }} • 
                                                    {{ $movement->performedBy->name }}
                                                </p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                @if($movement->status_before !== $movement->status_after)
                                                    <p class="mb-1">
                                                        Status: 
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-300">
                                                            {{ $movement->status_before }}
                                                        </span>
                                                        <i class="fas fa-arrow-right mx-2 text-gray-400"></i>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-300">
                                                            {{ $movement->status_after }}
                                                        </span>
                                                    </p>
                                                @endif
                                                
                                                @if($movement->fromDepartment || $movement->toDepartment)
                                                    <p class="mb-1">
                                                        <i class="fas fa-building text-gray-400 mr-1"></i>
                                                        @if($movement->fromDepartment && $movement->toDepartment)
                                                            {{ $movement->fromDepartment->name }} → {{ $movement->toDepartment->name }}
                                                        @elseif($movement->fromDepartment)
                                                            Von: {{ $movement->fromDepartment->name }}
                                                        @elseif($movement->toDepartment)
                                                            Nach: {{ $movement->toDepartment->name }}
                                                        @endif
                                                    </p>
                                                @endif

                                                @if($movement->fromContainer || $movement->toContainer)
                                                    <p class="mb-1">
                                                        <i class="fas fa-box text-gray-400 mr-1"></i>
                                                        @if($movement->fromContainer && $movement->toContainer)
                                                            {{ $movement->fromContainer->name }} → {{ $movement->toContainer->name }}
                                                        @elseif($movement->fromContainer)
                                                            Von Container: {{ $movement->fromContainer->name }}
                                                        @elseif($movement->toContainer)
                                                            In Container: {{ $movement->toContainer->name }}
                                                        @endif
                                                    </p>
                                                @endif

                                                @if($movement->notes)
                                                    <p class="text-gray-600 italic">{{ $movement->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    @if($instrument->movements->count() > 10)
                        <div class="text-center pt-4 border-t-2 border-gray-200">
                            <a href="{{ route('movements.index', ['instrument' => $instrument->id]) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Alle {{ $instrument->movements->count() }} Bewegungen anzeigen
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Defect Reports -->
            @if($instrument->defectReports->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Defektmeldungen ({{ $instrument->defectReports->count() }})</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($instrument->defectReports->take(5) as $report)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <a href="{{ route('defect-reports.show', $report) }}" 
                                           class="font-medium text-blue-600 hover:text-blue-800">
                                            {{ $report->report_number }}
                                        </a>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                            @switch($report->status)
                                                @case('reported') bg-yellow-100 text-yellow-800 @break
                                                @case('acknowledged') bg-blue-100 text-blue-800 @break
                                                @case('in_review') bg-purple-100 text-purple-800 @break
                                                @case('ordered') bg-indigo-100 text-indigo-800 @break
                                                @case('received') bg-green-100 text-green-800 @break
                                                @case('repaired') bg-green-100 text-green-800 @break
                                                @case('closed') bg-gray-100 text-gray-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch">
                                            {{ $report->status_display }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">{{ $report->description }}</p>
                                    <div class="text-xs text-gray-500">
                                        Gemeldet von {{ $report->reportedBy->name }} am {{ $report->reported_at->format('d.m.Y H:i') }}
                                        @if($report->reportingDepartment)
                                            • {{ $report->reportingDepartment->name }}
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                        @switch($report->severity)
                                            @case('low') bg-green-100 text-green-800 @break
                                            @case('medium') bg-yellow-100 text-yellow-800 @break
                                            @case('high') bg-orange-100 text-orange-800 @break
                                            @case('critical') bg-red-100 text-red-800 @break
                                        @endswitch">
                                        {{ $report->severity_display }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if($instrument->defectReports->count() > 5)
                        <div class="text-center pt-4">
                            <a href="{{ route('defect-reports.index', ['search' => $instrument->serial_number]) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                Alle {{ $instrument->defectReports->count() }} Defektmeldungen anzeigen
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Defektmeldungen</h2>
                </div>
                <div class="p-6">
                    <div class="text-center py-4">
                        <svg class="w-12 h-12 text-green-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-500">Keine Defektmeldungen vorhanden</p>
                        <p class="text-sm text-gray-400 mt-1">Dieses Instrument hat bisher keine gemeldeten Defekte</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Verlauf</h2>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <!-- Purchase -->
                            @if($instrument->purchase_date)
                            <li>
                                <div class="relative pb-8">
                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-500">Instrument angeschafft</p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>{{ $instrument->purchase_date->format('d.m.Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif

                            <!-- Recent Defect Reports -->
                            @foreach($instrument->defectReports->sortByDesc('reported_at')->take(3) as $report)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full 
                                                @if($report->status === 'closed') bg-green-500 
                                                @elseif($report->status === 'in_review') bg-yellow-500 
                                                @else bg-red-500 
                                                @endif 
                                                flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Defekt gemeldet: 
                                                    <a href="{{ route('defect-reports.show', $report) }}" class="font-medium text-blue-600 hover:text-blue-800">
                                                        {{ $report->report_number }}
                                                    </a>
                                                </p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>{{ $report->reported_at->format('d.m.Y H:i') }} • {{ $report->reportedBy->name }}</p>
                                                <p class="text-gray-600">{{ Str::limit($report->description, 100) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
