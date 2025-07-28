<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Defektmeldung bearbeiten</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Bearbeiten Sie die Defektmeldung {{ $report->report_number }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('defect-reports.show', $report) }}" 
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
                                <option value="">OP-Saal auswählen (optional)</option>
                                @foreach($operating_rooms as $room)
                                    <option value="{{ $room->id }}">
                                        {{ $room->name }} ({{ $room->department->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('operating_room_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Defekttyp -->
                        <div>
                            <label for="defect_type_id" class="block text-sm font-medium text-gray-700 mb-2">Defekttyp *</label>
                            <select wire:model.live="defect_type_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                                <option value="">Defekttyp auswählen</option>
                                @foreach($defectTypes as $defectType)
                                    <option value="{{ $defectType->id }}">{{ $defectType->name }}</option>
                                @endforeach
                            </select>
                            @error('defect_type_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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

                    <!-- Status (nur für Bearbeitung) -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select wire:model.live="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                required>
                            <option value="reported">Gemeldet</option>
                            <option value="acknowledged">Bestätigt</option>
                            <option value="in_review">In Bearbeitung</option>
                            <option value="ordered">Bestellt</option>
                            <option value="received">Erhalten</option>
                            <option value="repaired">Repariert</option>
                            <option value="closed">Abgeschlossen</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Beschreibung -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Beschreibung des Defekts *</label>
                        <textarea wire:model="description" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                  placeholder="Beschreiben Sie den Defekt so detailliert wie möglich..."
                                  required></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Bestehende Fotos -->
                    @if(count($existing_photos) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vorhandene Fotos</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                @foreach($existing_photos as $index => $photo)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $photo) }}" class="w-full h-24 object-cover rounded border border-gray-200">
                                        <button type="button" 
                                                wire:click="removeExistingPhoto({{ $index }})"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Neue Fotos hinzufügen -->
                    <div>
                        <label for="photos" class="block text-sm font-medium text-gray-700 mb-2">Neue Fotos hinzufügen (optional)</label>
                        <input type="file" 
                               wire:model="photos" 
                               multiple 
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('photos.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Neue Foto-Vorschau -->
                    @if($photos)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Neue Foto-Vorschau</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($photos as $photo)
                                    <div class="relative">
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-24 object-cover rounded border border-gray-200">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>Änderungen speichern</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Wird gespeichert...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
