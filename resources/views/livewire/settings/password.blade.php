<section class="w-full">
    <x-settings.layout heading="Passwort ändern" subheading="Stellen Sie sicher, dass Ihr Konto ein langes, zufälliges Passwort verwendet, um sicher zu bleiben">
        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Aktuelles Passwort</label>
                <input 
                    type="password" 
                    id="current_password"
                    wire:model="current_password"
                    required
                    autocomplete="current-password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                />
                @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Neues Passwort</label>
                <input 
                    type="password" 
                    id="password"
                    wire:model="password"
                    required
                    autocomplete="new-password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                />
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Passwort bestätigen</label>
                <input 
                    type="password" 
                    id="password_confirmation"
                    wire:model="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                />
                @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Passwort aktualisieren</span>
                    <span wire:loading>Wird aktualisiert...</span>
                </button>
                
                <div x-data="{ show: false }" 
                     x-show="show" 
                     x-transition
                     @password-updated.window="show = true; setTimeout(() => show = false, 3000)"
                     style="display: none;"
                     class="text-sm text-green-600 font-medium">
                    Passwort aktualisiert!
                </div>
            </div>
        </form>
    </x-settings.layout>
</section>
