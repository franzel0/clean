<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Neue Defektmeldung</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Erstellen Sie eine neue Defektmeldung für ein chirurgisches Instrument
                    </p>
                </div>
                <div>
                    <a href="{{ route('defect-reports.index') }}" 
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
                <form wire:submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Instrument auswählen -->
                <div>
                    <label for="instrument" class="block text-sm font-medium text-gray-700 mb-2">Instrument *</label>
                    <select wire:model.live="instrument_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                            required>
                        <option value="">Instrument auswählen</option>
                        @foreach($instruments as $instrument)
                            <option value="{{ $instrument->id }}">
                                {{ $instrument->name }} ({{ $instrument->serial_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('instrument_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- OP-Saal -->
                <div>
                    <label for="operating_room" class="block text-sm font-medium text-gray-700 mb-2">OP-Saal</label>
                    <select wire:model.live="operating_room_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">OP-Saal auswählen</option>
                        @foreach($operating_rooms as $room)
                            <option value="{{ $room->id }}">
                                {{ $room->name }} ({{ $room->department->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('operating_room_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Defekttyp -->
                <div>
                    <label for="defect_type" class="block text-sm font-medium text-gray-700 mb-2">Defekttyp *</label>
                    <select wire:model.live="defect_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                            required>
                        <option value="">Defekttyp auswählen</option>
                        <option value="broken">Kaputt</option>
                        <option value="dull">Stumpf</option>
                        <option value="bent">Verbogen</option>
                        <option value="missing_parts">Fehlende Teile</option>
                        <option value="other">Sonstiges</option>
                    </select>
                    @error('defect_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Schweregrad -->
                <div>
                    <label for="severity" class="block text-sm font-medium text-gray-700 mb-2">Schweregrad *</label>
                    <select wire:model.live="severity" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                            required>
                        <option value="low">Niedrig</option>
                        <option value="medium">Mittel</option>
                        <option value="high">Hoch</option>
                        <option value="critical">Kritisch</option>
                    </select>
                    @error('severity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Beschreibung -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Beschreibung *</label>
                <textarea wire:model="description" 
                          rows="4"
                          placeholder="Detaillierte Beschreibung des Defekts..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                          required></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Fotos -->
            <div>
                <label for="photos" class="block text-sm font-medium text-gray-700 mb-2">Fotos (optional)</label>
                <input type="file" 
                       wire:model="photos" 
                       multiple 
                       accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-sm text-gray-500 mt-1">Maximal 2MB pro Bild. Unterstützte Formate: JPG, PNG, GIF</p>
                @error('photos.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Foto-Vorschau -->
            @if($photos)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto-Vorschau</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($photos as $photo)
                            <div class="relative">
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-24 object-cover rounded border border-gray-200">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('defect-reports.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Abbrechen
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Defektmeldung erstellen
                </button>
            </div>
        </form>
            </div>
        </div>
    </div>
</div>
