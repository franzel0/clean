<div class="relative" x-data="{ open: @entangle('showDropdown') }">
    <!-- Input Field -->
    <div class="relative">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="query"
            wire:focus="showAll"
            placeholder="{{ $placeholder }}"
            class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $error ? 'border-red-500' : '' }}"
            autocomplete="off"
            {{ $required ? 'required' : '' }}
        />
        
        <!-- Clear Button -->
        @if($selectedValue)
            <button 
                type="button"
                wire:click="clearSelection"
                class="absolute inset-y-0 right-8 flex items-center text-gray-400 hover:text-gray-600"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        @endif
        
        <!-- Dropdown Arrow -->
        <button 
            type="button"
            wire:click="showAll"
            class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

    <!-- Hidden Input for Form Submission -->
    @if($name)
        <input type="hidden" name="{{ $name }}" value="{{ $selectedValue }}" />
    @endif

    <!-- Dropdown -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        @click.away="$wire.hideDropdown()"
        class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
        style="display: none;"
    >
        @if(empty($filteredOptions))
            <div class="px-3 py-2 text-sm text-gray-500">
                Keine Ergebnisse gefunden
            </div>
        @else
            @foreach($filteredOptions as $option)
                <div 
                    wire:click="selectOption('{{ $option[$valueField] }}')"
                    class="px-3 py-2 text-sm cursor-pointer hover:bg-blue-50 hover:text-blue-900 border-b border-gray-100 last:border-b-0 {{ $selectedValue == $option[$valueField] ? 'bg-blue-100 text-blue-900' : 'text-gray-900' }}"
                >
                    <div class="font-medium">{{ $option[$displayField] }}</div>
                    @if($secondaryDisplayField && isset($option[$secondaryDisplayField]))
                        <div class="text-xs text-gray-500">{{ $option[$secondaryDisplayField] }}</div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <!-- Error Message -->
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
