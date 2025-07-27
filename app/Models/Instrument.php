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
        'manufacturer',
        'model',
        'category',
        'purchase_price',
        'purchase_date',
        'warranty_until',
        'description',
        'status',
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

        static::saved(function ($instrument) {
            // Update container status when instrument status or container changes
            if ($instrument->isDirty(['status', 'current_container_id']) && $instrument->currentContainer) {
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
        return match($this->status) {
            'available' => 'Verfügbar',
            'in_use' => 'Im Einsatz',
            'defective' => 'Defekt',
            'in_repair' => 'In Reparatur',
            'out_of_service' => 'Außer Betrieb',
            default => ucfirst($this->status),
        };
    }

    public function getCategoryDisplayAttribute(): string
    {
        return match($this->category) {
            'scissors' => 'Scheren',
            'forceps' => 'Pinzetten',
            'scalpel' => 'Skalpelle',
            'clamp' => 'Klemmen',
            'retractor' => 'Wundhaken',
            'needle_holder' => 'Nadelhalter',
            default => ucfirst($this->category),
        };
    }

    public function getIsUnderWarrantyAttribute(): bool
    {
        return $this->warranty_until && $this->warranty_until->isFuture();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefective($query)
    {
        return $query->where('status', 'defective');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
