<section class="w-full">
    <x-settings.layout heading="Profil" subheading="Aktualisieren Sie Ihren Namen und Ihre E-Mail-Adresse">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input 
                    type="text" 
                    id="name"
                    wire:model="name"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-Mail-Adresse</label>
                <input 
                    type="email" 
                    id="email"
                    wire:model="email"
                    required
                    autocomplete="email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                />
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            Ihre E-Mail-Adresse ist nicht verifiziert.
                            <button type="button" wire:click.prevent="resendVerificationNotification" class="text-blue-600 hover:text-blue-500 cursor-pointer">
                                Klicken Sie hier, um die Verifizierungs-E-Mail erneut zu senden.
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm font-medium text-green-600">
                                Ein neuer Verifizierungslink wurde an Ihre E-Mail-Adresse gesendet.
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Abteilung</label>
                <select 
                    id="department_id"
                    wire:model="department_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">Abteilung auswählen...</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Speichern</span>
                    <span wire:loading>Wird gespeichert...</span>
                </button>
                
                <div x-data="{ show: false }" 
                     x-show="show" 
                     x-transition
                     @profile-updated.window="show = true; setTimeout(() => show = false, 3000)"
                     style="display: none;"
                     class="text-sm text-green-600 font-medium">
                    Gespeichert!
                </div>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
