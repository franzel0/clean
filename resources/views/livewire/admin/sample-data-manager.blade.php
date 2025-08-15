<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Beispieldaten-Verwaltung</h1>
        <p class="mt-2 text-gray-600">Verwalte Beispieldaten für die Anwendung</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Status der Beispieldaten</h3>
        </div>
        
        <div class="p-6">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0">
                    @if($hasSampleData)
                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @else
                        <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900">
                        @if($hasSampleData)
                            Beispieldaten sind vorhanden
                        @else
                            Keine Beispieldaten gefunden
                        @endif
                    </h4>
                    <p class="text-sm text-gray-500">
                        @if($hasSampleData)
                            Die Datenbank enthält bereits Instrumente und möglicherweise andere Beispieldaten.
                        @else
                            Die Datenbank ist leer und bereit für Beispieldaten.
                        @endif
                    </p>
                </div>
            </div>

            @if(!$hasSampleData)
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Was enthalten die Beispieldaten?</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• 5 Container mit verschiedenen Instrumentensets</li>
                        <li>• 20 verschiedene Instrumente (Scheren, Pinzetten, Endoskopie, etc.)</li>
                        <li>• 3 Defektmeldungen mit unterschiedlichen Prioritäten</li>
                        <li>• 3 Beispiel-Bestellungen in verschiedenen Status</li>
                        <li>• Zusätzliche Benutzer für verschiedene Rollen</li>
                    </ul>
                </div>

                <div class="flex space-x-3">
                    <button 
                        wire:click="importSampleData" 
                        onclick="return confirm('Sind Sie sicher, dass Sie Beispieldaten importieren möchten? Dies fügt Instrumente, Container, Defektmeldungen und Bestellungen hinzu.')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                        Beispieldaten importieren
                    </button>
                </div>
            @else
                <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <h4 class="text-sm font-medium text-yellow-900 mb-2">Datenbank zurücksetzen</h4>
                    <p class="text-sm text-yellow-800">
                        <strong>Achtung:</strong> Diese Aktion löscht alle Instrumente, Container, Defektmeldungen und Bestellungen aus der Datenbank. 
                        Die Grundkonfiguration (Kategorien, Status, etc.) bleibt erhalten.
                    </p>
                </div>

                <div class="flex space-x-3">
                    <button 
                        wire:click="clearAllData" 
                        onclick="return confirm('ACHTUNG: Diese Aktion löscht ALLE Instrumente, Container, Defektmeldungen und Bestellungen unwiderruflich! Sind Sie absolut sicher?')"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Alle Daten löschen
                    </button>

                    <button 
                        wire:click="importSampleData" 
                        onclick="return confirm('Dies fügt weitere Beispieldaten zu den vorhandenen Daten hinzu. Fortfahren?')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Weitere Beispieldaten hinzufügen
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Hinweise zur Verwendung</h3>
        <div class="space-y-3 text-sm text-gray-700">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p><strong>Grundkonfiguration:</strong> Kategorien, Status, Hersteller und Lieferanten werden immer beibehalten.</p>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p><strong>Beispieldaten:</strong> Perfekt zum Testen und Kennenlernen der Anwendung.</p>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p><strong>Admin-Benutzer:</strong> admin@hospital.de / password</p>
            </div>
        </div>
    </div>
</div>
