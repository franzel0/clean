<div class="min-h-screen flex items-center justify-center bg-gray-50 py-8 px-4">
  <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 flex flex-col gap-6">
    <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="resetPassword" class="flex flex-col gap-6">
      <!-- Email Address -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
        <input id="email" name="email" type="email" wire:model="email" required autocomplete="email" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        >
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
        <input id="password" name="password" type="password" wire:model="password" required autocomplete="new-password" placeholder="{{ __('Password') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        >
      </div>

      <!-- Confirm Password -->
      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirm password') }}</label>
        <input id="password_confirmation" name="password_confirmation" type="password" wire:model="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm password') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        >
      </div>

      <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition-colors duration-200 text-base mt-2">
        {{ __('Reset password') }}
      </button>
    </form>
  </div>
</div>
