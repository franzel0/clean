<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo -->
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-24 w-auto object-contain">
            </div>
        </div>
        
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ config('app.name') }}</h1>
            <h2 class="text-xl text-gray-700">Konto erstellen</h2>
            <p class="mt-2 text-sm text-gray-600">Geben Sie Ihre Details ein, um ein Konto zu erstellen</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-center text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="register" class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                    Vollständiger Name
                </label>
                <input wire:model="name" 
                       id="name"
                       type="text" 
                       required 
                       autofocus 
                       autocomplete="name"
                       placeholder="Vollständiger Name"
                       class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    E-Mail-Adresse
                </label>
                <input wire:model="email" 
                       id="email"
                       type="email" 
                       required 
                       autocomplete="email"
                       placeholder="email@example.com"
                       class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Passwort
                </label>
                <input wire:model="password" 
                       id="password"
                       type="password" 
                       required 
                       autocomplete="new-password"
                       placeholder="Passwort"
                       class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    Passwort bestätigen
                </label>
                <input wire:model="password_confirmation" 
                       id="password_confirmation"
                       type="password" 
                       required 
                       autocomplete="new-password"
                       placeholder="Passwort bestätigen"
                       class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Konto erstellen</span>
                    <span wire:loading class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Wird erstellt...
                    </span>
                </button>
            </div>
        </form>

        <div class="text-center">
            <span class="text-sm text-gray-600">Bereits ein Konto?</span>
            <a href="{{ route('login') }}" 
               wire:navigate
               class="text-sm text-blue-600 hover:text-blue-500 ml-1">
                Anmelden
            </a>
        </div>
    </div>
</div>
