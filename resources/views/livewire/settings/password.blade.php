<section class="w-full">
 @include('partials.settings-heading')

 <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
 <form wire:submit="updatePassword" class="mt-6 space-y-6">
 <div>
     <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Current password') }}</label>
     <input 
         type="password" 
         id="current_password"
         wire:model="current_password"
         required
         autocomplete="current-password"
         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
     />
 </div>
 
 <div>
     <label for="password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('New password') }}</label>
     <input 
         type="password" 
         id="password"
         wire:model="password"
         required
         autocomplete="new-password"
         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
     />
 </div>
 
 <div>
     <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Confirm Password') }}</label>
     <input 
         type="password" 
         id="password_confirmation"
         wire:model="password_confirmation"
         required
         autocomplete="new-password"
         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
     />
 </div>

 <div class="flex items-center gap-4">
 <div class="flex items-center justify-end">
 <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">{{ __('Save') }}</button>
 </div>

 <x-action-message class="me-3" on="password-updated">
 {{ __('Saved.') }}
 </x-action-message>
 </div>
 </form>
 </x-settings.layout>
</section>
