<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Defektmeldung {{ $report->report_number }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Erstellt am {{ $report->created_at->format('d.m.Y H:i') }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('defect-reports.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 mr-3">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Zurück
                    </a>
                    <a href="{{ route('defect-reports.edit', $report) }}"
                       wire:navigate
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Bearbeiten
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <!-- Instrument Details -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Instrument</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Name</dt>
                                <dd class="text-sm text-gray-900">{{ $report->instrument->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Seriennummer</dt>
                                <dd class="text-sm text-gray-900">{{ $report->instrument->serial_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Typ</dt>
                                <dd class="text-sm text-gray-900">{{ $report->instrument->type }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Hersteller</dt>
                                <dd class="text-sm text-gray-900">{{ $report->instrument->manufacturer }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Instrumentenstatus</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $report->instrument->instrumentStatus->bg_class }} {{ $report->instrument->instrumentStatus->text_class }}">
                                        {{ $report->instrument->instrumentStatus->name }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Meldungsdetails</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Bearbeitungsstatus</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $report->is_completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $report->is_completed ? 'Abgeschlossen' : 'Offen' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Defekttyp</dt>
                                <dd class="text-sm text-gray-900">{{ $report->defect_type_display }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Schweregrad</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $report->severity_badge_class }}">
                                        {{ $report->severity_display }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-700">Gemeldet von</dt>
                                <dd class="text-sm text-gray-900">{{ $report->reportedBy->name }}</dd>
                            </div>
                            @if($report->operating_room_id)
                                <div>
                                    <dt class="text-sm font-medium text-gray-700">OP-Saal</dt>
                                    <dd class="text-sm text-gray-900">{{ $report->operatingRoom->name }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Beschreibung</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700">{{ $report->description }}</p>
                    </div>
                </div>

                <!-- Resolution Notes -->
                @if($report->is_completed && $report->resolution_notes)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Lösungsnotizen</h3>
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <p class="text-sm text-gray-700">{{ $report->resolution_notes }}</p>
                            @if($report->resolved_at)
                                <p class="text-xs text-gray-500 mt-2">
                                    Gelöst am {{ $report->resolved_at->format('d.m.Y H:i') }}
                                    @if($report->resolvedBy)
                                        von {{ $report->resolvedBy->name }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Photos -->
                @if($report->photos && count($report->photos) > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Fotos</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($report->photos as $photo)
                                <div class="relative group">
                                    <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="block">
                                        <img src="{{ asset('storage/' . $photo) }}" 
                                             alt="Defektfoto" 
                                             class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:shadow-lg transition-shadow duration-200">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Timeline -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Zeitlinie</h3>
                    <div class="flow-root">
                        <ul class="-mb-8">
                            

                            <!-- Meldung bestätigt -->
                            @if($report->acknowledged_at)
                                <li>
                                    <div class="relative pb-8">
                                        @if($report->is_completed)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Meldung bestätigt
                                                        @if($report->acknowledgedBy)
                                                            von <span class="font-medium text-gray-900">{{ $report->acknowledgedBy->name }}</span>
                                                        @endif
                                                        <time datetime="{{ $report->acknowledged_at->toISOString() }}" class="block">
                                                            {{ $report->acknowledged_at->format('d.m.Y H:i') }}
                                                        </time>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            <!-- Meldung gelöst -->
                            @if($report->is_completed && $report->resolved_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Meldung abgeschlossen
                                                        @if($report->resolvedBy)
                                                            von <span class="font-medium text-gray-900">{{ $report->resolvedBy->name }}</span>
                                                        @endif
                                                        <time datetime="{{ $report->resolved_at->toISOString() }}" class="block">
                                                            {{ $report->resolved_at->format('d.m.Y H:i') }}
                                                        </time>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
