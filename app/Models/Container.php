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
        'type',
        'type_id',
        'description',
        'is_active',
        'status',
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
        return match($this->type) {
            'surgical_set' => 'Chirurgie-Set',
            'basic_set' => 'Basis-Set',
            'special_set' => 'Spezial-Set',
            default => ucfirst($this->type),
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'complete' => 'Vollständig',
            'incomplete' => 'Unvollständig',
            'out_of_service' => 'Außer Betrieb',
            default => ucfirst($this->status),
        };
    }

    public function updateStatusBasedOnInstruments(): void
    {
        $hasDefectiveInstruments = $this->instruments()
            ->whereIn('status_id', [3, 4, 5, 6]) // Wartung, Außer Betrieb, Verloren/Vermisst, Aussortiert
            ->exists();

        $newStatus = $hasDefectiveInstruments ? 'incomplete' : 'complete';

        if ($this->status !== $newStatus) {
            $this->update(['status' => $newStatus]);
        }
    }

    public function getUnavailableInstrumentsCountAttribute(): int
    {
        return $this->instruments()
            ->whereIn('status_id', [3, 4, 5, 6]) // Wartung, Außer Betrieb, Verloren/Vermisst, Aussortiert
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
