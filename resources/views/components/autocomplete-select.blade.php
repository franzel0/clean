@props([
    'options' => [],
    'value' => null,
    'name' => '',
    'placeholder' => 'Auswählen...',
    'required' => false,
    'displayField' => 'name',
    'valueField' => 'id', 
    'searchFields' => ['name'],
    'secondaryDisplayField' => null,
    'error' => null
])

<div>
    @livewire('components.autocomplete-select', [
        'options' => $options,
        'value' => $value,
        'name' => $name,
        'placeholder' => $placeholder,
        'required' => $required,
        'displayField' => $displayField,
        'valueField' => $valueField,
        'searchFields' => $searchFields,
        'secondaryDisplayField' => $secondaryDisplayField
    ])
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
