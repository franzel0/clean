<div class="relative">
    <div class="dropdown">
        <button 
            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
            onclick="this.nextElementSibling.classList.toggle('hidden')"
        >
            <span class="mr-2">{{ $languages[$currentLocale]['flag'] }}</span>
            <span>{{ $languages[$currentLocale]['name'] }}</span>
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <div class="absolute right-0 z-50 hidden mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg dropdown-menu">
            <div class="py-1">
                @foreach($languages as $code => $language)
                    <button 
                        wire:click="switchLanguage('{{ $code }}')"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 
                               {{ $currentLocale === $code ? 'bg-blue-50 text-blue-700' : '' }}"
                    >
                        <span class="mr-3">{{ $language['flag'] }}</span>
                        <span>{{ $language['name'] }}</span>
                        @if($currentLocale === $code)
                            <svg class="w-4 h-4 ml-auto text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = event.target.closest('.dropdown');
    if (!dropdown) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});
</script>
