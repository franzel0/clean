@props([
    'options' => [],
    'wireModel' => '',
    'value' => null,
    'placeholder' => 'Auswählen...',
    'required' => false,
    'displayField' => 'name',
    'valueField' => 'id', 
    'searchFields' => ['name'],
    'secondaryDisplayField' => null,
    'error' => null,
    'name' => ''
])

@php
    $componentId = 'autocomplete_' . uniqid();
@endphp

<div 
    x-data="{
        options: {{ json_encode($options) }},
        searchFields: {{ json_encode($searchFields) }},
        displayField: '{{ $displayField }}',
        valueField: '{{ $valueField }}',
        secondaryDisplayField: '{{ $secondaryDisplayField }}',
        wireModel: '{{ $wireModel }}',
        
        query: '',
        selectedValue: {{ $value ? json_encode($value) : 'null' }},
        filteredOptions: {{ json_encode($options) }},
        showDropdown: false,
        
        init() {
            if (this.selectedValue) {
                this.setInitialQuery();
            }
            this.filteredOptions = this.options;
        },
        
        setInitialQuery() {
            const option = this.options.find(opt => opt[this.valueField] == this.selectedValue);
            if (option) {
                this.query = this.getDisplayText(option);
            }
        },
        
        getDisplayText(option) {
            let text = option[this.displayField];
            if (this.secondaryDisplayField && option[this.secondaryDisplayField]) {
                text += ' (' + option[this.secondaryDisplayField] + ')';
            }
            return text;
        },
        
        updateQuery() {
            if (!this.query || this.query.length === 0) {
                this.filteredOptions = this.options;
                this.showDropdown = false;
                this.clearSelection();
                return;
            }
            
            this.filteredOptions = this.options.filter(option => {
                return this.searchFields.some(field => {
                    return option[field] && option[field].toLowerCase().includes(this.query.toLowerCase());
                });
            });
            
            this.showDropdown = true;
            
            // Check for exact match
            const exactMatch = this.filteredOptions.find(option => {
                return this.getDisplayText(option).toLowerCase() === this.query.toLowerCase();
            });
            
            if (exactMatch) {
                this.selectOption(exactMatch);
            } else if (this.selectedValue) {
                // Clear selection if text doesn't match
                this.clearSelection();
            }
        },
        
        selectOption(option) {
            this.selectedValue = option[this.valueField];
            this.query = this.getDisplayText(option);
            this.showDropdown = false;
            this.updateWireModel();
        },
        
        clearSelection() {
            this.selectedValue = null;
            this.query = '';
            this.showDropdown = false;
            this.filteredOptions = this.options;
            this.updateWireModel();
        },
        
        updateWireModel() {
            if (this.wireModel && typeof $wire !== 'undefined') {
                $wire.set(this.wireModel, this.selectedValue);
            }
        },
        
        showAll() {
            this.filteredOptions = this.options;
            this.showDropdown = true;
        },
        
        hideDropdown() {
            this.showDropdown = false;
        },
        
        delayedHideDropdown() {
            // Delay to prevent immediate hiding when clicking buttons or input
            setTimeout(() => {
                if (document.activeElement !== this.$refs.input) {
                    this.showDropdown = false;
                }
            }, 150);
        }
    }"
    class="relative"
    id="{{ $componentId }}"
>
    <!-- Input Field -->
    <div class="relative">
        <input 
            type="text" 
            x-model="query"
            x-ref="input"
            @input="updateQuery()"
            @focus="showAll()"
            @blur="delayedHideDropdown()"
            placeholder="{{ $placeholder }}"
            class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ $error ? 'border-red-500' : '' }}"
            autocomplete="off"
            {{ $required ? 'required' : '' }}
        />
        
        <!-- Hidden input for form submission -->
        @if($name)
            <input type="hidden" name="{{ $name }}" x-model="selectedValue" />
        @endif
        
        <!-- Clear Button -->
        <button 
            type="button"
            x-show="query && query.length > 0"
            @mousedown.prevent="clearSelection(); $refs.input.focus()"
            class="absolute inset-y-0 right-8 flex items-center text-gray-400 hover:text-gray-600"
            style="display: none;"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Dropdown Arrow -->
        <button 
            type="button"
            @mousedown.prevent="showAll(); $refs.input.focus()"
            class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

    <!-- Dropdown -->
    <div 
        x-show="showDropdown" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        @mousedown.prevent
        class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
        style="display: none;"
    >
        <template x-if="filteredOptions.length === 0">
            <div class="px-3 py-2 text-sm text-gray-500">
                Keine Ergebnisse gefunden
            </div>
        </template>
        
        <template x-for="option in filteredOptions" :key="option[valueField]">
            <div 
                @mousedown.prevent="selectOption(option)"
                class="px-3 py-2 text-sm cursor-pointer hover:bg-blue-50 hover:text-blue-900 border-b border-gray-100 last:border-b-0"
                :class="selectedValue == option[valueField] ? 'bg-blue-100 text-blue-900' : 'text-gray-900'"
            >
                <div class="font-medium" x-text="option[displayField]"></div>
                <template x-if="secondaryDisplayField && option[secondaryDisplayField]">
                    <div class="text-xs text-gray-500" x-text="option[secondaryDisplayField]"></div>
                </template>
            </div>
        </template>
    </div>

    <!-- Error Message -->
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
