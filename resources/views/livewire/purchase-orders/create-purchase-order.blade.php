<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Neue Bestellung</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Erstellen Sie eine neue Bestellung für Ersatzinstrumente
                    </p>
                </div>
                <div>
                    <a href="{{ route('purchase-orders.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Zurück
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <form wire:submit="save">
        <!-- Defektmeldung auswählen -->
        <div class="mb-6">
            <label for="defect_report_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                Defektmeldung *
                <div class="ml-2 relative group">
                    <svg class="w-4 h-4 text-gray-400 cursor-help" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="invisible group-hover:visible absolute left-0 top-6 bg-gray-800 text-white text-xs rounded py-2 px-3 w-72 z-10 shadow-lg">
                        <strong>Angezeigte Defektmeldungen:</strong><br>
                        • Status: "Bestätigt" oder "In Bearbeitung"<br>
                        • Noch keiner Bestellung zugeordnet<br>
                        • Bereit für Ersatzbeschaffung
                    </div>
                </div>
            </label>
            <select wire:model.live="defect_report_id" 
                    id="defect_report_id" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @if($defectReports->count() > 0)
                    <option value="">Defektmeldung auswählen...</option>
                    @foreach($defectReports as $report)
                        <option value="{{ $report->id }}">
                            {{ $report->instrument->name }} - {{ $report->instrument->serial_number }} 
                            ({{ $report->created_at->format('d.m.Y') }}) - {{ $report->status_display }}
                        </option>
                    @endforeach
                @else
                    <option value="" disabled>Keine Defektmeldungen verfügbar</option>
                @endif
            </select>
            @if($defectReports->count() === 0)
                <p class="mt-1 text-sm text-amber-600">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Keine Defektmeldungen mit Status "Bestätigt" oder "In Bearbeitung" verfügbar.
                </p>
            @endif
            @error('defect_report_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Ausgewählte Defektmeldung anzeigen -->
        @if($this->selectedDefectReport)
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-medium text-blue-900 mb-2">Ausgewählte Defektmeldung</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Instrument:</span>
                        <span class="text-gray-900">{{ $this->selectedDefectReport->instrument->name }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Seriennummer:</span>
                        <span class="text-gray-900">{{ $this->selectedDefectReport->instrument->serial_number }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Defektbeschreibung:</span>
                        <span class="text-gray-900">{{ $this->selectedDefectReport->description }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Gemeldet von:</span>
                        <span class="text-gray-900">{{ $this->selectedDefectReport->reportedBy->name }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Lieferant -->
        <div class="mb-6">
            <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">
                Lieferant *
            </label>
            <input type="text" 
                   wire:model="supplier" 
                   id="supplier" 
                   placeholder="Name des Lieferanten eingeben..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('supplier')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Geschätzte Kosten -->
        <div class="mb-6">
            <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-2">
                Geschätzte Kosten (€)
            </label>
            <input type="number" 
                   wire:model="estimated_cost" 
                   id="estimated_cost" 
                   step="0.01" 
                   min="0" 
                   max="999999.99"
                   placeholder="0.00"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('estimated_cost')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Voraussichtliches Lieferdatum -->
        <div class="mb-6">
            <label for="estimated_delivery_date" class="block text-sm font-medium text-gray-700 mb-2">
                Voraussichtliches Lieferdatum
            </label>
            <input type="date" 
                   wire:model="estimated_delivery_date" 
                   id="estimated_delivery_date" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('estimated_delivery_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notizen -->
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                Notizen
            </label>
            <textarea wire:model="notes" 
                      id="notes" 
                      rows="4" 
                      placeholder="Zusätzliche Informationen zur Bestellung..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
            <a href="{{ route('purchase-orders.index') }}" 
               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                Abbrechen
            </a>
            <button type="submit" 
                    class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Bestellung erstellen</span>
                <span wire:loading class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Wird erstellt...
                </span>
            </button>
        </div>
    </form>
            </div>
        </div>
    </div>
</div>
