<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Neuen Container erstellen</h1>
                    <p class="text-gray-600 mt-1">Einen neuen Container für Instrumente anlegen</p>
                </div>
                <a href="{{ route('containers.index') }}" 
                   class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors border-2 border-gray-300">
                    <i class="fas fa-arrow-left mr-2"></i>Zurück
                </a>
            </div>
        </div>

        <!-- Create Form Card -->
        <div class="bg-white rounded-xl shadow-sm border-2 border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-green-50 border-b-2 border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-plus text-green-600 mr-2"></i>
                    Container Daten
                </h2>
            </div>
            
            <form wire:submit="save" class="p-6 space-y-6">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Container Name *
                    </label>
                    <input 
                        type="text" 
                        id="name"
                        wire:model="name" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                        placeholder="z.B. Chirurgie Basis Set A1"
                    >
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Barcode Field -->
                <div>
                    <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">
                        Barcode *
                    </label>
                    <input 
                        type="text" 
                        id="barcode"
                        wire:model="barcode" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                        placeholder="z.B. CON-2024-001"
                    >
                    @error('barcode')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type Field -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Container Typ *
                    </label>
                    <select 
                        id="type"
                        wire:model="type" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                    >
                        <option value="">Typ auswählen...</option>
                        <option value="surgical_set">Chirurgie Set</option>
                        <option value="basic_set">Basis Set</option>
                        <option value="special_set">Spezial Set</option>
                    </select>
                    @error('type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Field -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Beschreibung
                    </label>
                    <textarea 
                        id="description"
                        wire:model="description" 
                        rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                        placeholder="Optionale Beschreibung des Container-Inhalts..."
                    ></textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="is_active"
                        wire:model="is_active" 
                        class="w-4 h-4 text-green-600 border-2 border-gray-300 rounded focus:ring-green-500"
                        checked
                    >
                    <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                        Container ist aktiv
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t-2 border-gray-200">
                    <a href="{{ route('containers.index') }}" 
                       class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Abbrechen
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors border-2 border-green-600"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>
                            <i class="fas fa-plus mr-2"></i>Container erstellen
                        </span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin mr-2"></i>Erstellen...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="mt-6 bg-green-50 border-2 border-green-200 text-green-800 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif
    </div>
</div>
