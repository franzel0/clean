<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode',
        'type_id',
        'description',
        'is_active',
        'status_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function instruments(): HasMany
    {
        return $this->hasMany(Instrument::class, 'current_container_id');
    }

    public function containerType(): BelongsTo
    {
        return $this->belongsTo(ContainerType::class, 'type_id');
    }

    public function containerStatus(): BelongsTo
    {
        return $this->belongsTo(ContainerStatus::class, 'status_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTypeDisplayAttribute(): string
    {
        if (!$this->type) {
            return 'Unbekannter Typ';
        }
        
        return match($this->type) {
            'surgical_set' => 'Chirurgie-Set',
            'basic_set' => 'Basis-Set',
            'special_set' => 'Spezial-Set',
            default => ucfirst($this->type),
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        if (!$this->status) {
            return 'Unbekannter Status';
        }
        
        return match($this->status) {
            'complete' => 'Vollständig',
            'incomplete' => 'Unvollständig',
            'out_of_service' => 'Außer Betrieb',
            default => ucfirst($this->status),
        };
    }

    public function getBarcodeDisplayAttribute(): string
    {
        return $this->barcode ?? 'Kein Barcode';
    }

    public function getDescriptionDisplayAttribute(): string
    {
        return $this->description ?? 'Keine Beschreibung';
    }

    public function getInstrumentCountAttribute(): int
    {
        return $this->instruments?->count() ?? 0;
    }

    public function getTypeDisplayFromRelationAttribute(): string
    {
        return $this->containerType?->name ?? $this->type_display;
    }

    public function getStatusDisplayFromRelationAttribute(): string
    {
        return $this->containerStatus?->name ?? $this->status_display;
    }

    public function updateStatusBasedOnInstruments(): void
    {
        // Benutze den neuen InstrumentStatusService
        $statusService = app(\App\Services\InstrumentStatusService::class);
        $newStatusName = $statusService->calculateContainerStatus($this);
        
        $newStatus = \App\Models\ContainerStatus::where('name', $newStatusName)->first();
        
        if ($newStatus && $this->status_id !== $newStatus->id) {
            $this->update(['status_id' => $newStatus->id]);
        }
    }

    public function getUnavailableInstrumentsCountAttribute(): int
    {
        // Status-IDs für nicht verfügbare Instrumente (basierend auf den neuen Status)
        $unavailableStatusIds = \App\Models\InstrumentStatus::whereIn('name', [
            'Defekt gemeldet', 'Defekt bestätigt', 'In Reparatur', 
            'Aussortiert', 'Verloren/Vermisst', 'In Wartung'
        ])->pluck('id')->toArray();
        
        return $this->instruments()
            ->whereIn('status_id', $unavailableStatusIds)
            ->count();
    }

    public function scopeComplete($query)
    {
        return $query->where('status', 'complete');
    }

    public function scopeIncomplete($query)
    {
        return $query->where('status', 'incomplete');
    }
}
