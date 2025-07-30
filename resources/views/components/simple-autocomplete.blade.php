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
    'error' => null
])

<div>
    @livewire('components.simple-autocomplete', [
        'options' => $options,
        'value' => $value,
        'placeholder' => $placeholder,
        'wireModel' => $wireModel,
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
