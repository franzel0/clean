@if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-2xl max-w-md w-full max-h-96 overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100 border-b-2 border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-building text-blue-600 mr-2"></i>Abteilungen verwalten
                </h2>
                <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Content -->
            <div class="overflow-y-auto flex-1 px-6 py-4">
                <div class="space-y-3">
                    @forelse($departments as $department)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $department->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $department->code }}</p>
                            </div>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" 
                                       wire:model.live="departmentStates.{{ $department->id }}"
                                       class="w-5 h-5 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-medium @if($departmentStates[$department->id]) text-green-600 @else text-red-600 @endif">
                                    @if($departmentStates[$department->id])
                                        Aktiv
                                    @else
                                        Inaktiv
                                    @endif
                                </span>
                            </label>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Keine Abteilungen gefunden</p>
                    @endforelse
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t-2 border-gray-200 flex justify-end space-x-3">
                <button wire:click="closeModal"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-colors border border-gray-300">
                    Abbrechen
                </button>
                <button wire:click="save"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors border border-blue-600">
                    <i class="fas fa-save mr-1"></i>Speichern
                </button>
            </div>
        </div>
    </div>
@endif
