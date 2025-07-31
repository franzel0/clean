<div class="min-h-screen flex items-center justify-center bg-gray-50 py-8 px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 flex flex-col gap-6">
        <x-auth-header :title="__('Passwort vergessen')" :description="__('Geben Sie Ihre E-Mail-Adresse ein, um einen Link zum Zurücksetzen des Passworts zu erhalten')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
            <!-- Email Address -->
            <div>
                <label for="email"
                    class="block text-sm font-medium text-gray-700 mb-2">{{ __('E-Mail-Adresse') }}</label>
                <input type="email" id="email" wire:model="email" required autofocus placeholder="email@example.com"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">{{ __('Schicke mir den Link zum Zurücksetzen des Passworts') }}</button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ __('oder kehr zurück zur') }}</span>
            <a href="{{ route('login') }}" wire:navigate
                class="text-blue-600 hover:text-blue-800">{{ __('Anmeldung') }}</a>
        </div>
    </div>
</div>