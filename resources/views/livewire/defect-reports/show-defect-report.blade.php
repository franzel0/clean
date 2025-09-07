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
                <!-- Status Badge -->
                <div class="mb-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border-2 outline outline-2 outline-offset-2 {{ $report->instrument->instrumentStatus->bg_class }} {{ $report->instrument->instrumentStatus->text_class }} border-current">
                        {{ $report->instrument->instrumentStatus->name }}
                    </span>
                </div>

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
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Meldungsdetails</h3>
                        <dl class="space-y-3">
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
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Defektmeldung erstellt
                                                    <time datetime="{{ $report->created_at->toISOString() }}">
                                                        {{ $report->created_at->format('d.m.Y H:i') }}
                                                    </time>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
