<?php

namespace App\Livewire\Containers;

use App\Models\Container;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Layout('components.layouts.app')]
#[Title('Neuen Container erstellen')]
class CreateContainer extends Component
{
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

    public function save()
    {
        $this->validate();

        $container = Container::create([
            'name' => $this->name,
            'barcode' => $this->barcode,
            'type' => $this->type,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'status' => 'complete', // New containers start as complete
        ]);

        session()->flash('success', 'Container wurde erfolgreich erstellt.');
        
        return redirect()->route('containers.show', $container);
    }

    public function render()
    {
        return view('livewire.containers.create-container');
    }
}
