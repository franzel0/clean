<?php

namespace App\Livewire\Containers;

use App\Models\Container;
use App\Models\Instrument;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.app')]
#[Title('Container anzeigen')]
class ShowContainer extends Component
{
    public Container $container;
    public $showAssignModal = false;
    public $availableInstruments = [];

    public function mount(Container $container)
    {
        try {
            $this->container = $container->load([
                'instruments.defectReports',
                'instruments.instrumentStatus',
                'instruments.category',
                'instruments.manufacturerRelation'
            ]);
            $this->availableInstruments = collect([]);
        } catch (\Exception $e) {
            Log::error('Error in ShowContainer mount: ' . $e->getMessage());
            $this->container = $container; // Fallback ohne Relations
            $this->availableInstruments = collect([]);
        }
    }

    public function openAssignModal()
    {
        try {
            // Get instruments that are not assigned to any container
            $this->availableInstruments = Instrument::whereNull('current_container_id')
                ->where('is_active', true)
                ->with(['category', 'manufacturerRelation', 'instrumentStatus'])
                ->orderBy('name')
                ->get();
                
            Log::info('Available instruments count: ' . $this->availableInstruments->count());
            
            $this->showAssignModal = true;
            $this->dispatch('modal-opened');
        } catch (\Exception $e) {
            Log::error('Error in openAssignModal: ' . $e->getMessage());
            session()->flash('error', 'Fehler beim Öffnen des Modals: ' . $e->getMessage());
        }
    }

    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->availableInstruments = collect([]);
        $this->dispatch('modal-closed');
    }

    public function assignInstrument($instrumentId)
    {
        try {
            $instrument = Instrument::find($instrumentId);
            
            if ($instrument) {
                $instrument->update(['current_container_id' => $this->container->id]);
                
                // Reload container with instruments
                $this->container = $this->container->fresh()->load([
                    'instruments.defectReports',
                    'instruments.instrumentStatus',
                    'instruments.category',
                    'instruments.manufacturerRelation'
                ]);
                
                $this->closeAssignModal();
                
                session()->flash('message', 'Instrument "' . $instrument->name . '" wurde erfolgreich zum Container hinzugefügt.');
            } else {
                session()->flash('error', 'Instrument nicht gefunden.');
            }
        } catch (\Exception $e) {
            Log::error('Error in assignInstrument: ' . $e->getMessage());
            session()->flash('error', 'Fehler beim Zuweisen des Instruments: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.containers.show-container');
    }
}
