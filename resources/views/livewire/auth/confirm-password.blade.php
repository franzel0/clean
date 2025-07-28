<div class="flex flex-col gap-6">
 <x-auth-header
 :title="__('Confirm password')"
 :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
 />

 <!-- Session Status -->
 <x-auth-session-status class="text-center" :status="session('status')" />

 <form wire:submit="confirmPassword" class="flex flex-col gap-6">
 <!-- Password -->
 <div>
     <label for="password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Password') }}</label>
     <input 
         type="password" 
         id="password"
         wire:model="password"
         required
         autocomplete="new-password"
         placeholder="{{ __('Password') }}"
         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
     />
 </div>

 <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">{{ __('Confirm') }}</button>
 </form>
</div>
