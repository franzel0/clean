<?php

namespace App\Livewire\Help;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Workflow-Anleitung')]
class WorkflowGuide extends Component
{
    public function render()
    {
        return view('livewire.help.workflow-guide');
    }
}
