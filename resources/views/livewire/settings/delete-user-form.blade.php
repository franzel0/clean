<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <h2 class="text-xl font-semibold text-gray-900">Konto löschen</h2>
        <p class="text-sm text-gray-600">Löschen Sie Ihr Konto und alle zugehörigen Ressourcen</p>
    </div>

    <div x-data="{ showModal: false }">
        <button 
            @click="showModal = true"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Konto löschen
        </button>

        <div x-show="showModal" class="fixed inset-0 overflow-y-auto h-full w-full z-50" style="background-color: rgba(75, 85, 99, 0.5);" x-cloak>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <form wire:submit="deleteUser" class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Sind Sie sicher, dass Sie Ihr Konto löschen möchten?</h3>

                        <p class="text-sm text-gray-500 mt-2">
                            Sobald Ihr Konto gelöscht ist, werden alle zugehörigen Ressourcen und Daten dauerhaft gelöscht. Bitte geben Sie Ihr Passwort ein, um zu bestätigen, dass Sie Ihr Konto dauerhaft löschen möchten.
                        </p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Passwort</label>
                        <input 
                            type="password" 
                            id="password"
                            wire:model="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                        />
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button 
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Abbrechen
                        </button>

                        <button 
                            type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Konto löschen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
