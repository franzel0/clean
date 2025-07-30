<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'serial_number',
        'manufacturer_id',
        'model',
        'category_id',
        'purchase_price',
        'purchase_date',
        'warranty_until',
        'description',
        'status_id',
        'current_container_id',
        'current_location_id',
        'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'purchase_date' => 'date',
        'warranty_until' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($instrument) {
            // Log status change as movement if status_id is changing
            if ($instrument->isDirty('status_id') && $instrument->getOriginal('status_id')) {
                $oldStatusId = $instrument->getOriginal('status_id');
                $newStatusId = $instrument->status_id;
                
                // Create movement manually to avoid recursion
                \App\Models\InstrumentMovement::create([
                    'instrument_id' => $instrument->id,
                    'movement_type' => 'transfer', // Default type
                    'status_before' => $oldStatusId,
                    'status_after' => $newStatusId,
                    'moved_by' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                    'notes' => 'Status automatisch geändert',
                    'moved_at' => now(),
                ]);
            }
        });

        static::saved(function ($instrument) {
            // Update container status when instrument status or container changes
            if ($instrument->isDirty(['status_id', 'current_container_id']) && $instrument->currentContainer) {
                $instrument->currentContainer->updateStatusBasedOnInstruments();
            }
            
            // Also update the old container if container assignment changed
            if ($instrument->isDirty(['current_container_id']) && $instrument->getOriginal('current_container_id')) {
                $oldContainer = Container::find($instrument->getOriginal('current_container_id'));
                if ($oldContainer) {
                    $oldContainer->updateStatusBasedOnInstruments();
                }
            }
        });
    }

    public function currentContainer(): BelongsTo
    {
        return $this->belongsTo(Container::class, 'current_container_id');
    }

    public function currentLocation(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'current_location_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(InstrumentCategory::class, 'category_id');
    }

    public function instrumentStatus(): BelongsTo
    {
        return $this->belongsTo(InstrumentStatus::class, 'status_id');
    }

    public function manufacturerRelation(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function defectReports(): HasMany
    {
        return $this->hasMany(DefectReport::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InstrumentMovement::class);
    }

    public function getStatusDisplayAttribute(): string
    {
        return $this->instrumentStatus?->name ?? 'Unbekannt';
    }

    public function getCategoryDisplayAttribute(): string
    {
        return $this->category?->name ?? 'Unbekannt';
    }

    public function getIsUnderWarrantyAttribute(): bool
    {
        return $this->warranty_until && $this->warranty_until > now();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefective($query)
    {
        return $query->whereHas('instrumentStatus', function($q) {
            $q->where('name', 'LIKE', '%defekt%')->orWhere('name', 'LIKE', '%außer betrieb%');
        });
    }

    public function scopeAvailable($query)
    {
        return $query->whereHas('instrumentStatus', function($q) {
            $q->where('name', 'LIKE', '%verfügbar%');
        });
    }
}
