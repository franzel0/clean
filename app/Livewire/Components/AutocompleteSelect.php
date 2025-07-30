<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class AutocompleteSelect extends Component
{
    public $query = '';
    public $selectedValue = null;
    public $selectedLabel = '';
    public $options = [];
    public $filteredOptions = [];
    public $showDropdown = false;
    public $placeholder = '';
    public $name = '';
    public $required = false;
    public $error = '';
    public $wireModel = '';
    
    // Konfiguration
    public $displayField = 'name'; // Welches Feld für die Anzeige verwendet wird
    public $valueField = 'id'; // Welches Feld für den Wert verwendet wird
    public $searchFields = ['name']; // Felder in denen gesucht wird
    public $secondaryDisplayField = null; // Zusätzliches Feld für die Anzeige (z.B. serial_number)

    protected $listeners = ['resetAutocomplete'];

    public function mount($options = [], $value = null, $placeholder = 'Auswählen...', $name = '', $required = false, $displayField = 'name', $valueField = 'id', $searchFields = ['name'], $secondaryDisplayField = null, $wireModel = '')
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
        $this->name = $name;
        $this->required = $required;
        $this->displayField = $displayField;
        $this->valueField = $valueField;
        $this->searchFields = is_array($searchFields) ? $searchFields : [$searchFields];
        $this->secondaryDisplayField = $secondaryDisplayField;
        $this->wireModel = $wireModel;
        
        if ($value) {
            $this->selectOption($value);
        }
    }

    public function updatedQuery()
    {
        if (empty($this->query)) {
            $this->filteredOptions = $this->options;
            $this->showDropdown = false;
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
    }

    public function selectOption($optionValue)
    {
        $option = collect($this->options)->firstWhere($this->valueField, $optionValue);
        
        if ($option) {
            $this->selectedValue = $option[$this->valueField];
            $this->selectedLabel = $option[$this->displayField];
            
            if ($this->secondaryDisplayField && isset($option[$this->secondaryDisplayField])) {
                $this->query = $option[$this->displayField] . ' (' . $option[$this->secondaryDisplayField] . ')';
            } else {
                $this->query = $option[$this->displayField];
            }
            
            $this->showDropdown = false;
            
            // Wenn wireModel gesetzt ist, aktualisiere das Parent-Property direkt
            if ($this->wireModel) {
                $this->dispatch('autocomplete-wire-model-updated', [
                    'property' => $this->wireModel,
                    'value' => $this->selectedValue
                ]);
            }
            
            // Emit event to parent component (für Rückwärtskompatibilität)
            $this->dispatch('autocomplete-selected', [
                'name' => $this->name,
                'value' => $this->selectedValue,
                'option' => $option
            ]);
        }
    }

    public function clearSelection()
    {
        $this->selectedValue = null;
        $this->selectedLabel = '';
        $this->query = '';
        $this->showDropdown = false;
        $this->filteredOptions = $this->options;
        
        // Wenn wireModel gesetzt ist, setze das Parent-Property auf null
        if ($this->wireModel) {
            $this->dispatch('autocomplete-wire-model-updated', [
                'property' => $this->wireModel,
                'value' => null
            ]);
        }
        
        $this->dispatch('autocomplete-cleared', ['name' => $this->name]);
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

    public function resetAutocomplete()
    {
        $this->clearSelection();
    }

    public function render()
    {
        return view('livewire.components.autocomplete-select');
    }
}
