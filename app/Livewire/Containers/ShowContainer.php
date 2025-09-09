<?php

namespace App\Livewire\Containers;

use App\Models\Container;
use App\Models\Instrument;
use App\Models\InstrumentStatus;
use App\Models\ContainerStatisticsSetting;
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
    public $availableInstruments;
    public $statistics = [];
    public $statisticsCards = [];

    public function mount(Container $container)
    {
        try {
            $this->container = $container->load([
                'instruments.defectReports',
                'instruments.instrumentStatus',
                'instruments.category',
                'instruments.manufacturerRelation',
                'containerType'
            ]);

            // Statistiken berechnen
            $this->calculateStatistics();
            
            // Initialize as Collection
            $this->availableInstruments = collect();

        } catch (\Exception $e) {
            Log::error('Error in ShowContainer mount: ' . $e->getMessage());
            session()->flash('error', 'Fehler beim Laden des Containers: ' . $e->getMessage());
        }
    }

    private function calculateStatistics()
    {
        $instruments = $this->container->instruments;

        // Hole alle Status-Namen und gruppiere Instrumente
        $statusCounts = $instruments->groupBy(function($instrument) {
            return $instrument->instrumentStatus?->id ?? 0;
        })->map->count();

        // Lade aktive Container-Statistik-Einstellungen
        $activeSettings = ContainerStatisticsSetting::where('is_active', true)
            ->with('instrumentStatus')
            ->orderBy('sort_order')
            ->get();

        // Erstelle Karten basierend auf den Einstellungen
        $this->statisticsCards = $activeSettings->map(function($setting) use ($statusCounts) {
            $count = $statusCounts->get($setting->instrument_status_id, 0);
            
            return [
                'display_name' => $setting->display_name,
                'count' => $count,
                'color' => $setting->color,
                'status_name' => $setting->instrumentStatus?->name ?? 'Unbekannt'
            ];
        })->toArray();

        // Backward compatibility - behalte alte Statistik-Struktur für andere Teile der App
        $this->statistics = [
            'total' => $instruments->count(),
            'available' => $statusCounts->get($this->getStatusIdByName('Verfügbar'), 0),
            'in_use' => $statusCounts->get($this->getStatusIdByName('In Betrieb'), 0) + 
                       $statusCounts->get($this->getStatusIdByName('Im Einsatz'), 0),
            'in_repair' => $statusCounts->get($this->getStatusIdByName('In Reparatur'), 0),
            'defective' => $statusCounts->get($this->getStatusIdByName('Defekt gemeldet'), 0) + 
                          $statusCounts->get($this->getStatusIdByName('Defekt bestätigt'), 0) + 
                          $statusCounts->get($this->getStatusIdByName('Außer Betrieb'), 0) +
                          $statusCounts->get($this->getStatusIdByName('Ersatz bestellt'), 0),
            'status_breakdown' => $statusCounts->toArray()
        ];

        // Debug-Ausgabe
        Log::info('Container Statistiken berechnet:', [
            'container_id' => $this->container->id,
            'statistics' => $this->statistics
        ]);
    }

    private function getStatusIdByName($name)
    {
        static $statusIds = [];
        
        if (!isset($statusIds[$name])) {
            $status = InstrumentStatus::where('name', $name)->first();
            $statusIds[$name] = $status?->id ?? 0;
        }
        
        return $statusIds[$name];
    }

    public $instrumentFilter = '';

    public function openAssignModal()
    {
        try {
            $this->instrumentFilter = '';
            // Get instruments that are not assigned to any container
            $this->availableInstruments = \App\Models\Instrument::whereNull('current_container_id')
                ->where('is_active', true)
                ->with(['category', 'manufacturerRelation', 'instrumentStatus'])
                ->orderBy('name')
                ->get();
            $this->showAssignModal = true;
            $this->dispatch('modal-opened');
        } catch (\Exception $e) {
            Log::error('Error in openAssignModal: ' . $e->getMessage());
            session()->flash('error', 'Fehler beim Öffnen des Modals: ' . $e->getMessage());
        }
    }

    public function updatedInstrumentFilter()
    {
        // Trigger re-rendering when filter changes
        $this->dispatch('instruments-filtered');
    }

    public function getFilteredInstrumentsProperty()
    {
        if (empty($this->instrumentFilter)) {
            return $this->availableInstruments;
        }

        $searchTerm = strtolower($this->instrumentFilter);

        return $this->availableInstruments->filter(function ($instrument) use ($searchTerm) {
            return str_contains(strtolower($instrument->name), $searchTerm) ||
                str_contains(strtolower($instrument->serial_number), $searchTerm) ||
                str_contains(strtolower($instrument->category_display), $searchTerm) ||
                ($instrument->manufacturerRelation && str_contains(strtolower($instrument->manufacturerRelation->name), $searchTerm));
        });
    }

    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->instrumentFilter = '';
        $this->dispatch('modal-closed');
    }

    public function assignInstrument($instrumentId)
    {
        try {
            $instrument = \App\Models\Instrument::findOrFail($instrumentId);

            if ($instrument) {
                $instrument->update(['current_container_id' => $this->container->id]);

                // Reload container with instruments
                $this->container = $this->container->fresh()->load([
                    'instruments.defectReports',
                    'instruments.instrumentStatus',
                    'instruments.category',
                    'instruments.manufacturerRelation'
                ]);

                // Statistiken neu berechnen nach Instrument-Zuweisung
                $this->calculateStatistics();

                // Remove assigned instrument from available list
                $this->availableInstruments = $this->availableInstruments->reject(function ($item) use ($instrumentId) {
                    return $item->id == $instrumentId;
                });

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
