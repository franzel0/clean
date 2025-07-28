<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        @if($isEditing)
                            Instrument bearbeiten
                        @else
                            Neues Instrument erstellen
                        @endif
                    </h1>
                    <p class="text-gray-600 mt-1">
                        @if($isEditing)
                            Instrument {{ $instrument->name }} bearbeiten
                        @else
                            Ein neues Instrument in das System aufnehmen
                        @endif
                    </p>
                </div>
                <a href="{{ route('instruments.index') }}" 
                   class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors border-2 border-gray-300">
                    <i class="fas fa-arrow-left mr-2"></i>Zurück
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border-2 border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-blue-50 border-b-2 border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-tools text-blue-600 mr-2"></i>
                    Instrument Daten
                </h2>
            </div>

            @if (session()->has('message'))
                <div class="mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="save" class="p-6 space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Name *
                        </label>
                        <input type="text" 
                               wire:model="form.name" 
                               id="name"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('form.name') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Serial Number -->
                    <div>
                        <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Seriennummer *
                        </label>
                        <input type="text" 
                               wire:model="form.serial_number" 
                               id="serial_number"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('form.serial_number') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Manufacturer -->
                    <div>
                        <label for="manufacturer" class="block text-sm font-medium text-gray-700 mb-2">
                            Hersteller
                        </label>
                        <input type="text" 
                               wire:model="form.manufacturer" 
                               id="manufacturer"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('form.manufacturer') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                            Modell
                        </label>
                        <input type="text" 
                               wire:model="form.model" 
                               id="model"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('form.model') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategorie *
                        </label>
                        <select wire:model="form.category" 
                                id="category"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Kategorie wählen</option>
                            @foreach($categories as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('form.category') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status *
                        </label>
                        <select wire:model="form.status" 
                                id="status"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('form.status') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Purchase Price -->
                    <div>
                        <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Anschaffungspreis (€)
                        </label>
                        <input type="number" 
                               step="0.01"
                               wire:model="form.purchase_price" 
                               id="purchase_price"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('form.purchase_price') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Purchase Date -->
                    <div>
                        <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Anschaffungsdatum
                        </label>
                        <input type="date" 
                               wire:model.defer="form.purchase_date" 
                               id="purchase_date"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('form.purchase_date') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Warranty Until -->
                    <div>
                        <label for="warranty_until" class="block text-sm font-medium text-gray-700 mb-2">
                            Garantie bis
                        </label>
                        <input type="date" 
                               wire:model.defer="form.warranty_until" 
                               id="warranty_until"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('form.warranty_until') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Current Container -->
                    <div>
                        <label for="current_container_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Aktueller Container
                        </label>
                        <select wire:model="form.current_container_id" 
                                id="current_container_id"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Kein Container</option>
                            @foreach($containers as $container)
                                <option value="{{ $container->id }}">{{ $container->name }} ({{ $container->barcode }})</option>
                            @endforeach
                        </select>
                        @error('form.current_container_id') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Current Location -->
                    <div>
                        <label for="current_location_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Aktueller Standort
                        </label>
                        <select wire:model="form.current_location_id" 
                                id="current_location_id"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Kein Standort</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('form.current_location_id') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Beschreibung
                    </label>
                    <textarea wire:model="form.description" 
                              id="description"
                              rows="3"
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                    @error('form.description') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" 
                            wire:click="cancel"
                            class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors border-2 border-gray-300 font-medium">
                        Abbrechen
                    </button>
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors border-2 border-blue-600 font-medium">
                        @if($isEditing)
                            Aktualisieren
                        @else
                            Erstellen
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
