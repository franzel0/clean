<?php

namespace App\Livewire\Containers;

use App\Models\Container;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Layout('components.layouts.app')]
#[Title('Container bearbeiten')]
class EditContainer extends Component
{
    public Container $container;

    #[Validate('required|string|min:3|max:255')]
    public $name = '';

    #[Validate('required|string|min:3|max:255|unique:containers,barcode')]
    public $barcode = '';

    #[Validate('required|in:surgical_set,basic_set,special_set')]
    public $type = '';

    #[Validate('nullable|string|max:1000')]
    public $description = '';

    #[Validate('boolean')]
    public $is_active = true;

    public function mount(Container $container)
    {
        $this->container = $container;
        $this->name = $container->name;
        $this->barcode = $container->barcode;
        $this->type = $container->type;
        $this->description = $container->description;
        $this->is_active = $container->is_active;

        // Barcode unique rule anpassen für das aktuelle Container
        $this->resetValidation();
    }

    public function save()
    {
        // Barcode unique rule für aktuellen Container anpassen
        $rules = $this->rules();
        $rules['barcode'] = 'required|string|min:3|max:255|unique:containers,barcode,' . $this->container->id;
        
        $this->validate($rules);

        $this->container->update([
            'name' => $this->name,
            'barcode' => $this->barcode,
            'type' => $this->type,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Container wurde erfolgreich aktualisiert.');
        
        return redirect()->route('containers.show', $this->container);
    }

    public function render()
    {
        return view('livewire.containers.edit-container');
    }

    private function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'barcode' => 'required|string|min:3|max:255|unique:containers,barcode',
            'type' => 'required|in:surgical_set,basic_set,special_set',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];
    }
}
