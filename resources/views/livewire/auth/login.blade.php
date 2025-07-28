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
            <h2 class="text-3xl font-bold text-gray-900">Anmelden</h2>
            <p class="mt-2 text-sm text-gray-600">Geben Sie Ihre E-Mail und Ihr Passwort ein</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-center text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="login" class="space-y-6">
            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    E-Mail-Adresse
                </label>
                <input wire:model="email" 
                       id="email"
                       type="email" 
                       required 
                       autofocus 
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
                       autocomplete="current-password"
                       placeholder="Passwort"
                       class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                @if (Route::has('password.request'))
                    <div class="mt-2 text-right">
                        <a href="{{ route('password.request') }}" 
                           wire:navigate
                           class="text-sm text-blue-600 hover:text-blue-500">
                            Passwort vergessen?
                        </a>
                    </div>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input wire:model="remember" 
                       id="remember" 
                       type="checkbox"
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Angemeldet bleiben
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Anmelden</span>
                    <span wire:loading class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Wird angemeldet...
                    </span>
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="text-center">
                <span class="text-sm text-gray-600">Noch kein Konto?</span>
                <a href="{{ route('register') }}" 
                   wire:navigate
                   class="text-sm text-blue-600 hover:text-blue-500 ml-1">
                    Registrieren
                </a>
            </div>
        @endif
    </div>
</div>
