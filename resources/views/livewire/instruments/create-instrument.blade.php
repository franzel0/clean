<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Neues Instrument</h1>
                <p class="text-sm text-gray-600 mt-1">Fügen Sie ein neues Instrument zum System hinzu</p>
            </div>
            <a href="{{ route('instruments.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium border-2 border-gray-700 shadow-lg hover:shadow-xl transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Zurück zur Liste
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6">
        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           wire:model="name" 
                           id="name"
                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Serial Number -->
                <div>
                    <label for="serial_number" class="block text-sm font-bold text-gray-700 mb-2">
                        Seriennummer <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           wire:model="serial_number" 
                           id="serial_number"
                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('serial_number') border-red-500 @enderror">
                    @error('serial_number') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Manufacturer -->
                <div>
                    <label for="manufacturer" class="block text-sm font-bold text-gray-700 mb-2">
                        Hersteller
                    </label>
                    <input type="text" 
                           wire:model="manufacturer" 
                           id="manufacturer"
                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Model -->
                <div>
                    <label for="model" class="block text-sm font-bold text-gray-700 mb-2">
                        Modell
                    </label>
                    <input type="text" 
                           wire:model="model" 
                           id="model"
                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-bold text-gray-700 mb-2">
                        Kategorie <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="category" 
                            id="category"
                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                        <option value="">Kategorie wählen</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-bold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="status" 
                            id="status"
                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Purchase Price -->
                <div>
                    <label for="purchase_price" class="block text-sm font-bold text-gray-700 mb-2">
                        Kaufpreis (€)
                    </label>
                    <input type="number" 
                           wire:model="purchase_price" 
                           id="purchase_price"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Purchase Date -->
                <div>
                    <label for="purchase_date" class="block text-sm font-bold text-gray-700 mb-2">
                        Kaufdatum
                    </label>
                    <input type="date" 
                           wire:model="purchase_date" 
                           id="purchase_date"
                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Warranty Until -->
                <div>
                    <label for="warranty_until" class="block text-sm font-bold text-gray-700 mb-2">
                        Garantie bis
                    </label>
                    <input type="date" 
                           wire:model="warranty_until" 
                           id="warranty_until"
                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('warranty_until') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Container -->
                <div>
                    <label for="current_container_id" class="block text-sm font-bold text-gray-700 mb-2">
                        Container
                    </label>
                    <select wire:model="current_container_id" 
                            id="current_container_id"
                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Kein Container</option>
                        @foreach($containers as $container)
                            <option value="{{ $container->id }}">{{ $container->name }} ({{ $container->barcode }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Location -->
                <div>
                    <label for="current_location_id" class="block text-sm font-bold text-gray-700 mb-2">
                        Standort
                    </label>
                    <select wire:model="current_location_id" 
                            id="current_location_id"
                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Kein Standort</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">
                    Beschreibung
                </label>
                <textarea wire:model="description" 
                          id="description"
                          rows="4"
                          class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Zusätzliche Informationen zum Instrument..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('instruments.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-md text-sm font-bold border-2 border-gray-400 transition-all duration-200">
                    Abbrechen
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-bold border-2 border-blue-700 shadow-lg hover:shadow-xl transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>Instrument erstellen
                </button>
            </div>
        </form>
    </div>
</div>
