<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Bestellung {{ $order->order_number }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Bestelldetails und Status
                    </p>
                </div>
                <div class="flex space-x-3">
                    <!-- Status Actions -->
                    
                    <button wire:click="downloadPdf" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                        PDF herunterladen
                    </button>
                    <a href="{{ route('purchase-orders.index') }}" 
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
            
            @if(session()->has('message'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Status</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                        <!-- Instrumentenstatus -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-700">Instrumentenstatus</h3>
                            </div>
                            @if($order->defectReport && $order->defectReport->instrument && $order->defectReport->instrument->instrumentStatus)
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->defectReport->instrument->instrumentStatus->bg_class }} {{ $order->defectReport->instrument->instrumentStatus->text_class }}">
                                        {{ $order->defectReport->instrument->instrumentStatus->name }}
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-gray-600">
                                    Instrument: {{ $order->defectReport->instrument->name }} ({{ $order->defectReport->instrument->serial_number }})
                                </div>
                                <div class="mt-1 text-sm text-gray-600">
                                    Erstellt am {{ $order->requested_at ? $order->requested_at->format('d.m.Y H:i') : $order->created_at->format('d.m.Y H:i') }}
                                </div>
                            @else
                                <span class="text-gray-500 text-sm">Kein Instrument verknüpft</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Bestelldetails</h2>
                </div>
                <div class="p-6">
                    <!-- Replacement Instrument Section -->
                    <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Instrumentenaustausch</h3>
                        
                        <!-- Old Instrument Display -->
                        @if($order->oldInstrument)
                            <div class="mb-4 p-3 bg-white rounded border border-gray-300">
                                <p class="text-xs font-medium text-gray-600 mb-1">Zu ersetzendes Instrument</p>
                                <p class="text-sm font-medium text-gray-900">{{ $order->oldInstrument->name }}</p>
                                @if($order->oldInstrument->serial_number)
                                    <p class="text-xs text-gray-600">Seriennummer: {{ $order->oldInstrument->serial_number }}</p>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Replacement Type Selection -->
                        <div class="mb-4">
                            <label class="text-sm font-medium text-gray-700 mb-3 block">Art des Austauschs</label>
                            <div class="space-y-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" 
                                           wire:model.live="replacement_type" 
                                           value="same" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Gleiches Instrument nachbestellen</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" 
                                           wire:model.live="replacement_type" 
                                           value="alternative" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Alternative aus Katalog wählen</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" 
                                           wire:model.live="replacement_type" 
                                           value="description" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Beschreibung eingeben</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Alternative Instrument Selection -->
                        @if($replacement_type === 'alternative')
                            <div class="mb-4">
                                <label for="new_instrument_id" class="text-sm font-medium text-gray-700 mb-2 block">Alternatives Instrument</label>
                                <x-alpine-autocomplete 
                                    :options="$this->availableInstruments->toArray()"
                                    wire-model="new_instrument_id"
                                    :value="$new_instrument_id"
                                    placeholder="Instrument auswählen..."
                                    display-field="name"
                                    value-field="id"
                                    :search-fields="['name', 'serial_number']"
                                    secondary-display-field="serial_number"
                                    :error="$errors->first('new_instrument_id')"
                                />
                                @error('new_instrument_id') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                        @endif
                        
                        <!-- Description Input -->
                        @if($replacement_type === 'description')
                            <div class="mb-4">
                                <label for="replacement_instrument_description" class="text-sm font-medium text-gray-700 mb-2 block">Beschreibung des Ersatzinstruments</label>
                                <textarea id="replacement_instrument_description" 
                                          wire:model="replacement_instrument_description" 
                                          rows="3" 
                                          maxlength="500"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Beschreiben Sie das gewünschte Ersatzinstrument..."></textarea>
                                <p class="text-xs text-gray-500 mt-1">{{ strlen($replacement_instrument_description) }}/500 Zeichen</p>
                                @error('replacement_instrument_description') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                        @endif
                    </div>
                    
                    <!-- Current Details Display -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Bestellnummer</h3>
                            <p class="text-gray-900">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Angefordert von</h3>
                            <p class="text-gray-900">{{ $order->requestedBy->name }}</p>
                        </div>
                        @if($order->manufacturer_id)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Aktueller Hersteller</h3>
                                <p class="text-gray-900">{{ $order->manufacturer_display }}</p>
                            </div>
                        @endif
                        @if($order->estimated_cost)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Geschätzte Kosten</h3>
                                <p class="text-gray-900">{{ number_format($order->estimated_cost, 2) }} €</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Edit Form -->
                    <form wire:submit.prevent="updateDetails">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="manufacturer_id" class="text-sm font-medium text-gray-700 mb-2 block">Hersteller ändern</label>
                                <x-alpine-autocomplete 
                                    :options="$manufacturers->toArray()"
                                    wire-model="manufacturer_id"
                                    :value="$manufacturer_id"
                                    placeholder="Hersteller auswählen..."
                                    display-field="name"
                                    value-field="id"
                                    :search-fields="['name', 'contact_person']"
                                    secondary-display-field="contact_person"
                                    :error="$errors->first('manufacturer_id')"
                                />
                            </div>
                            <div>
                                <label for="instrumentStatusId" class="text-sm font-medium text-gray-700 mb-2 block">Instrumentenstatus</label>
                                <select wire:model="instrumentStatusId" 
                                        id="instrumentStatusId"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Status auswählen...</option>
                                    @foreach($this->availableInstrumentStatuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                                @error('instrumentStatusId') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                            <div>
                                <label for="actualCost" class="text-sm font-medium text-gray-700 mb-2 block">Tatsächliche Kosten</label>
                                <input type="number" 
                                       id="actualCost" 
                                       wire:model="actualCost" 
                                       step="0.01" 
                                       min="0" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0.00">
                                @error('actualCost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="expectedDelivery" class="text-sm font-medium text-gray-700 mb-2 block">Erwartete Lieferung</label>
                                <input type="date" 
                                       id="expectedDelivery" 
                                       wire:model="expectedDelivery" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                @error('expectedDelivery') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label for="notes" class="text-sm font-medium text-gray-700 mb-2 block">Notizen</label>
                            <textarea id="notes" 
                                      wire:model="notes" 
                                      rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Zusätzliche Informationen zur Bestellung..."></textarea>
                            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Abgeschlossen Status -->
                        <div class="mt-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       wire:model="is_completed" 
                                       id="is_completed"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_completed" class="ml-2 text-sm font-medium text-gray-700">
                                    Bestellung ist abgeschlossen
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Markieren Sie diese Option, wenn die Bestellung vollständig bearbeitet wurde.
                            </p>
                        </div>

                        <!-- Defektmeldung Abgeschlossen Status -->
                        @if($order->defectReport)
                            <div class="mt-6">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           wire:model="defect_report_completed" 
                                           id="defect_report_completed"
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <label for="defect_report_completed" class="ml-2 text-sm font-medium text-gray-700">
                                        Defektmeldung ist abgeschlossen
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Markieren Sie diese Option, wenn die zugehörige Defektmeldung behoben wurde.
                                </p>
                            </div>
                        @endif
                        
                        <div class="mt-6">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Details speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Related Defect Report -->
            @if($order->defectReport)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Zugehörige Defektmeldung</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Meldungsnummer</h3>
                                <p class="text-gray-900">
                                    <a href="{{ route('defect-reports.show', $order->defectReport) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        {{ $order->defectReport->report_number }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Instrument</h3>
                                <p class="text-gray-900">{{ $order->defectReport->instrument->name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Seriennummer</h3>
                                <p class="text-gray-900">{{ $order->defectReport->instrument->serial_number }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Gemeldet von</h3>
                                <p class="text-gray-900">{{ $order->defectReport->reportedBy->name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Abteilung</h3>
                                <p class="text-gray-900">{{ $order->defectReport->reportingDepartment->name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Defekttyp</h3>
                                <p class="text-gray-900">{{ $order->defectReport->defect_type_display }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Beschreibung</h3>
                            <p class="text-gray-900">{{ $order->defectReport->description }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statusverlauf Timeline -->
            @if($order->defectReport && $order->defectReport->instrument)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Statusverlauf</h2>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <!-- Bestellung erstellt -->
                                <li>
                                    <div class="relative pb-8">
                                        @if($movements->count() > 0 || $order->is_completed)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Bestellung erstellt von <span class="font-medium text-gray-900">{{ $order->requestedBy->name }}</span>
                                                        <time datetime="{{ $order->created_at->toISOString() }}" class="block">
                                                            {{ $order->created_at->format('d.m.Y H:i') }}
                                                        </time>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Instrumentenstatus-Änderungen -->
                                @foreach($movements as $index => $movement)
                                    <li>
                                        <div class="relative pb-8">
                                            @if($index < $movements->count() - 1 || $order->is_completed || ($order->defectReport && $order->defectReport->is_completed))
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-500">
                                                            Instrumentenstatus geändert
                                                            @if($movement->performedBy)
                                                                von <span class="font-medium text-gray-900">{{ $movement->performedBy->name }}</span>
                                                            @endif
                                                            <span class="block mt-1">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $movement->statusBeforeObject?->bg_class ?? 'bg-gray-100' }} {{ $movement->statusBeforeObject?->text_class ?? 'text-gray-800' }}">
                                                                    {{ $movement->status_before_display }}
                                                                </span>
                                                                <svg class="inline w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                                </svg>
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $movement->statusAfterObject?->bg_class ?? 'bg-gray-100' }} {{ $movement->statusAfterObject?->text_class ?? 'text-gray-800' }}">
                                                                    {{ $movement->status_after_display }}
                                                                </span>
                                                            </span>
                                                            <time datetime="{{ $movement->performed_at->toISOString() }}" class="block">
                                                                {{ $movement->performed_at->format('d.m.Y H:i') }}
                                                            </time>
                                                        </p>
                                                        @if($movement->notes)
                                                            <p class="text-xs text-gray-400 mt-1">{{ $movement->notes }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                                <!-- Bestellung abgeschlossen -->
                                @if($order->is_completed)
                                    <li>
                                        <div class="relative pb-8">
                                            @if($order->defectReport && $order->defectReport->is_completed)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
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
                                                            Bestellung abgeschlossen
                                                            <time datetime="{{ $order->updated_at->toISOString() }}" class="block">
                                                                {{ $order->updated_at->format('d.m.Y H:i') }}
                                                            </time>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif

                                <!-- Defektmeldung abgeschlossen -->
                                @if($order->defectReport && $order->defectReport->is_completed && $order->defectReport->resolved_at)
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-500">
                                                            Defektmeldung abgeschlossen
                                                            @if($order->defectReport->resolvedBy)
                                                                von <span class="font-medium text-gray-900">{{ $order->defectReport->resolvedBy->name }}</span>
                                                            @endif
                                                            <time datetime="{{ $order->defectReport->resolved_at->toISOString() }}" class="block">
                                                                {{ $order->defectReport->resolved_at->format('d.m.Y H:i') }}
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
            @endif
        </div>
    </div>
</div>
