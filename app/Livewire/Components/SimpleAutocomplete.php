<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class SimpleAutocomplete extends Component
{
    public $query = '';
    public $selectedValue = null;
    public $options = [];
    public $filteredOptions = [];
    public $showDropdown = false;
    public $placeholder = '';
    public $wireModel = '';
    public $required = false;
    
    // Konfiguration
    public $displayField = 'name';
    public $valueField = 'id';
    public $searchFields = ['name'];
    public $secondaryDisplayField = null;

    public function mount($options = [], $value = null, $placeholder = 'Auswählen...', $wireModel = '', $required = false, $displayField = 'name', $valueField = 'id', $searchFields = ['name'], $secondaryDisplayField = null)
    {
        // Convert Collection to array if needed
        if ($options instanceof Collection) {
            $this->options = $options->toArray();
        } elseif (is_object($options) && method_exists($options, 'toArray')) {
            $this->options = $options->toArray();
        } else {
            $this->options = is_array($options) ? $options : [];
        }
        
        $this->filteredOptions = $this->options;
        $this->placeholder = $placeholder;
        $this->wireModel = $wireModel;
        $this->required = $required;
        $this->displayField = $displayField;
        $this->valueField = $valueField;
        $this->searchFields = is_array($searchFields) ? $searchFields : [$searchFields];
        $this->secondaryDisplayField = $secondaryDisplayField;
        
        if ($value) {
            $this->selectOption($value);
        }
    }

    public function updatedQuery()
    {
        if (empty($this->query)) {
            $this->filteredOptions = $this->options;
            $this->showDropdown = false;
            // Nur das Parent-Model zurücksetzen, wenn wir wirklich nichts ausgewählt haben
            if ($this->selectedValue) {
                $this->selectedValue = null;
                $this->updateParentModel(null);
            }
            return;
        }

        $this->filteredOptions = array_filter($this->options, function ($option) {
            foreach ($this->searchFields as $field) {
                if (isset($option[$field]) && stripos($option[$field], $this->query) !== false) {
                    return true;
                }
            }
            return false;
        });

        $this->showDropdown = true;
        
        // Check if query exactly matches an option
        $exactMatch = collect($this->filteredOptions)->first(function ($option) {
            $displayText = $option[$this->displayField];
            if ($this->secondaryDisplayField && isset($option[$this->secondaryDisplayField])) {
                $displayText .= ' (' . $option[$this->secondaryDisplayField] . ')';
            }
            return $displayText === $this->query;
        });
        
        if ($exactMatch) {
            $this->selectedValue = $exactMatch[$this->valueField];
            $this->updateParentModel($this->selectedValue);
        } else {
            // Zurücksetzen nur wenn kein exakter Match gefunden wurde
            if ($this->selectedValue) {
                $this->selectedValue = null;
                $this->updateParentModel(null);
            }
        }
    }

    public function selectOption($optionValue)
    {
        $option = collect($this->options)->firstWhere($this->valueField, $optionValue);
        
        if ($option) {
            $this->selectedValue = $option[$this->valueField];
            
            if ($this->secondaryDisplayField && isset($option[$this->secondaryDisplayField])) {
                $this->query = $option[$this->displayField] . ' (' . $option[$this->secondaryDisplayField] . ')';
            } else {
                $this->query = $option[$this->displayField];
            }
            
            $this->showDropdown = false;
            $this->updateParentModel($this->selectedValue);
        }
    }

    private function updateParentModel($value)
    {
        if ($this->wireModel) {
            $this->dispatch('autocomplete-update', [
                'property' => $this->wireModel,
                'value' => $value
            ]);
        }
    }

    public function clearSelection()
    {
        $this->selectedValue = null;
        $this->query = '';
        $this->showDropdown = false;
        $this->filteredOptions = $this->options;
        $this->updateParentModel(null);
    }

    public function showAll()
    {
        $this->filteredOptions = $this->options;
        $this->showDropdown = true;
    }

    public function hideDropdown()
    {
        $this->showDropdown = false;
    }

    public function render()
    {
        return view('livewire.components.simple-autocomplete');
    }
}
