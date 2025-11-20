<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Workflow-Anleitung</h1>
            <p class="mt-2 text-lg text-gray-600">
                Verstehen Sie den Ablauf von der Instrumentenverwaltung bis zur Bestellung
            </p>
        </div>

        <!-- Workflow Overview -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Workflow-Übersicht</h2>
            </div>
            <div class="p-6">
                <div class="relative">
                    <!-- Timeline -->
                    <div class="space-y-8">
                        <!-- Schritt 1 -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-500 text-white font-bold flex-shrink-0">
                                1
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Instrumente und Container anlegen</h3>
                                <p class="mt-1 text-gray-600">
                                    Zunächst werden alle Instrumente im System erfasst und können in Container organisiert werden.
                                </p>
                                <ul class="mt-2 list-disc list-inside text-sm text-gray-600 space-y-1">
                                    <li>Instrumente haben einen Namen, Seriennummer, Typ und Hersteller</li>
                                    <li>Jedes Instrument hat einen Status (z.B. "Verfügbar", "In Verwendung", "Defekt")</li>
                                    <li>Container gruppieren mehrere Instrumente für OP-Säle oder Abteilungen</li>
                                    <li>Container können Statistiken tracken (z.B. Nutzungshäufigkeit)</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Arrow -->
                        <div class="ml-5 border-l-2 border-gray-300 h-8"></div>

                        <!-- Schritt 2 -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-yellow-500 text-white font-bold flex-shrink-0">
                                2
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Defektmeldung erstellen</h3>
                                <p class="mt-1 text-gray-600">
                                    Wenn ein Instrument defekt ist, wird eine Defektmeldung erstellt.
                                </p>
                                <ul class="mt-2 list-disc list-inside text-sm text-gray-600 space-y-1">
                                    <li>Wählen Sie das betroffene Instrument aus</li>
                                    <li>Geben Sie den Defekttyp an (z.B. "Kaputt", "Stumpf", "Verbogen")</li>
                                    <li>Beschreiben Sie den Defekt detailliert</li>
                                    <li>Bewerten Sie den Schweregrad (Niedrig, Mittel, Hoch, Kritisch)</li>
                                    <li>Optional: Fügen Sie Fotos des Defekts hinzu</li>
                                    <li>Der Instrumentenstatus wird automatisch aktualisiert (z.B. "Defekt gemeldet")</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Arrow -->
                        <div class="ml-5 border-l-2 border-gray-300 h-8"></div>

                        <!-- Schritt 3 -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-orange-500 text-white font-bold flex-shrink-0">
                                3
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Reaktion auf die Defektmeldung</h3>
                                <p class="mt-1 text-gray-600">
                                    Nach der Meldung gibt es mehrere Möglichkeiten zur Reaktion:
                                </p>
                                <div class="mt-3 space-y-3">
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <h4 class="font-medium text-blue-900">Option A: Reparatur</h4>
                                        <p class="text-sm text-blue-700 mt-1">
                                            Das Instrument wird zur Reparatur geschickt. Status ändert sich zu "In Reparatur".
                                            Nach erfolgreicher Reparatur wird die Defektmeldung als "Abgeschlossen" markiert.
                                        </p>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-lg">
                                        <h4 class="font-medium text-green-900">Option B: Ersatz bestellen</h4>
                                        <p class="text-sm text-green-700 mt-1">
                                            Wenn das Instrument nicht repariert werden kann oder ein Ersatz benötigt wird,
                                            erstellen Sie eine Bestellung (siehe Schritt 4).
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <h4 class="font-medium text-gray-900">Option C: Aussortieren</h4>
                                        <p class="text-sm text-gray-700 mt-1">
                                            Das Instrument wird als "Aussortiert" markiert und aus dem aktiven Bestand entfernt.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Arrow -->
                        <div class="ml-5 border-l-2 border-gray-300 h-8"></div>

                        <!-- Schritt 4 -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-purple-500 text-white font-bold flex-shrink-0">
                                4
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Bestellung erstellen (Optional)</h3>
                                <p class="mt-1 text-gray-600">
                                    Wenn ein Ersatzinstrument benötigt wird, wird eine Bestellung erstellt.
                                </p>
                                <ul class="mt-2 list-disc list-inside text-sm text-gray-600 space-y-1">
                                    <li>Die Bestellung wird mit der Defektmeldung verknüpft</li>
                                    <li>Geben Sie Hersteller, geschätzte Kosten und erwartetes Lieferdatum an</li>
                                    <li>Aktualisieren Sie den Instrumentenstatus während des Bestellprozesses</li>
                                    <li>Markieren Sie die Bestellung als "Abgeschlossen", wenn sie eingetroffen ist</li>
                                    <li>Schließen Sie auch die zugehörige Defektmeldung ab</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instrumentenstatus -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Instrumentenstatus-Übersicht</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-green-200 bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-green-900">Verfügbar</h3>
                        <p class="text-sm text-green-700 mt-1">
                            Das Instrument ist einsatzbereit und kann verwendet werden.
                        </p>
                    </div>

                    <div class="border border-blue-200 bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-blue-900">In Verwendung</h3>
                        <p class="text-sm text-blue-700 mt-1">
                            Das Instrument wird aktuell in einem OP-Saal oder einer Abteilung verwendet.
                        </p>
                    </div>

                    <div class="border border-yellow-200 bg-yellow-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-yellow-900">Defekt gemeldet</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            Eine Defektmeldung wurde erstellt, aber noch keine Maßnahme eingeleitet.
                        </p>
                    </div>

                    <div class="border border-orange-200 bg-orange-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-orange-900">In Reparatur</h3>
                        <p class="text-sm text-orange-700 mt-1">
                            Das Instrument befindet sich in der Reparatur.
                        </p>
                    </div>

                    <div class="border border-purple-200 bg-purple-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-purple-900">Sterilisation</h3>
                        <p class="text-sm text-purple-700 mt-1">
                            Das Instrument wird aktuell sterilisiert oder wartet auf Sterilisation.
                        </p>
                    </div>

                    <div class="border border-red-200 bg-red-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-red-900">Außer Betrieb</h3>
                        <p class="text-sm text-red-700 mt-1">
                            Das Instrument kann nicht verwendet werden und ist dauerhaft defekt.
                        </p>
                    </div>

                    <div class="border border-gray-200 bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-900">Aussortiert</h3>
                        <p class="text-sm text-gray-700 mt-1">
                            Das Instrument wurde aus dem aktiven Bestand entfernt.
                        </p>
                    </div>

                    <div class="border border-indigo-200 bg-indigo-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-indigo-900">Vermisst</h3>
                        <p class="text-sm text-indigo-700 mt-1">
                            Der Aufenthaltsort des Instruments ist unbekannt.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Defektmeldungsstatus -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Defektmeldungsstatus</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-yellow-200 bg-yellow-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-yellow-900">Offen</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            Die Defektmeldung wurde erstellt und wartet auf Bearbeitung. Das Feld "Abgeschlossen" ist nicht markiert.
                        </p>
                    </div>

                    <div class="border border-green-200 bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-green-900">Abgeschlossen</h3>
                        <p class="text-sm text-green-700 mt-1">
                            Der Defekt wurde behoben oder das Instrument ersetzt. Das Feld "Abgeschlossen" ist markiert und Lösungsnotizen wurden hinzugefügt.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bestellungsstatus -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Bestellungsstatus</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-yellow-200 bg-yellow-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-yellow-900">Offen</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            Die Bestellung wurde erstellt, ist aber noch nicht abgeschlossen. Das Feld "Abgeschlossen" ist nicht markiert.
                        </p>
                    </div>

                    <div class="border border-green-200 bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-green-900">Abgeschlossen</h3>
                        <p class="text-sm text-green-700 mt-1">
                            Die Bestellung ist eingetroffen und vollständig bearbeitet. Das Feld "Abgeschlossen" ist markiert.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statusverlauf/Timeline -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Statusverlauf (Timeline)</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    In den Detailansichten von Defektmeldungen und Bestellungen finden Sie eine Timeline, 
                    die alle wichtigen Ereignisse chronologisch darstellt:
                </p>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Erstellung</h4>
                            <p class="text-sm text-gray-600">Wann wurde die Meldung/Bestellung erstellt und von wem</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Statusänderungen</h4>
                            <p class="text-sm text-gray-600">Alle Änderungen am Instrumentenstatus mit Vorher/Nachher-Anzeige</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Abschluss</h4>
                            <p class="text-sm text-gray-600">Wann wurde die Meldung/Bestellung als abgeschlossen markiert</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Best Practices -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">Best Practices</h2>
            <ul class="space-y-2 text-blue-800">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Erstellen Sie Defektmeldungen so detailliert wie möglich, um die Problemanalyse zu erleichtern</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Aktualisieren Sie den Instrumentenstatus zeitnah, damit andere Benutzer den aktuellen Stand kennen</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Fügen Sie Fotos zu Defektmeldungen hinzu, um den Defekt besser zu dokumentieren</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Tragen Sie Lösungsnotizen ein, wenn Sie eine Defektmeldung abschließen</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Nutzen Sie die Timeline, um den Verlauf von Defektmeldungen und Bestellungen nachzuvollziehen</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Bei Bestellungen: Schließen Sie sowohl die Bestellung als auch die Defektmeldung ab, wenn der Ersatz eingetroffen ist</span>
                </li>
            </ul>
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Zurück zum Dashboard
            </a>
        </div>
    </div>
</div>
