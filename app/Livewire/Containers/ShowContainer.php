<?php

namespace App\Livewire\Containers;

use App\Models\Container;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Container anzeigen')]
class ShowContainer extends Component
{
    public Container $container;

    public function mount(Container $container)
    {
        $this->container = $container->load([
            'instruments.defectReports'
        ]);
    }

    public function render()
    {
        return view('livewire.containers.show-container');
    }
}
