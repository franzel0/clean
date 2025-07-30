<section class="w-full">
    <x-settings.layout heading="Erscheinungsbild" subheading="Aktualisieren Sie die Darstellungseinstellungen für Ihr Konto">
        <div x-data="{ appearance: 'light' }" class="flex rounded-lg border border-gray-300 overflow-hidden">
            <button @click="appearance = 'light'" :class="appearance === 'light' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="flex items-center px-4 py-2 text-sm font-medium border-r border-gray-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Hell
            </button>
            <button @click="appearance = 'dark'" :class="appearance === 'dark' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="flex items-center px-4 py-2 text-sm font-medium border-r border-gray-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                Dunkel
            </button>
            <button @click="appearance = 'system'" :class="appearance === 'system' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="flex items-center px-4 py-2 text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                System
            </button>
        </div>
        
        <div class="mt-6">
            <p class="text-sm text-gray-600">
                Wählen Sie Ihr bevorzugtes Erscheinungsbild für die Anwendung. Die Systemeinstellung verwendet das Theme Ihres Betriebssystems.
            </p>
        </div>
    </x-settings.layout>
</section>
