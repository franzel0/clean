<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstrumentStatus extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
        'is_active',
        'sort_order',
        'available_in_purchase_orders',
        'available_in_defect_reports',
        'available_in_instruments',
        'available_in_containers',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'available_in_purchase_orders' => 'boolean',
        'available_in_defect_reports' => 'boolean',
        'available_in_instruments' => 'boolean',
        'available_in_containers' => 'boolean',
    ];

    public function instruments(): HasMany
    {
        return $this->hasMany(Instrument::class, 'status_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailableInPurchaseOrders($query)
    {
        return $query->where('available_in_purchase_orders', true);
    }

    public function scopeAvailableInDefectReports($query)
    {
        return $query->where('available_in_defect_reports', true);
    }

    public function scopeAvailableInInstruments($query)
    {
        return $query->where('available_in_instruments', true);
    }

    public function scopeAvailableInContainers($query)
    {
        return $query->where('available_in_containers', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get Tailwind CSS classes for status badge based on color
     */
    public function getBadgeClassesAttribute(): array
    {
        return match($this->color) {
            '#10B981' => ['bg-green-100', 'text-green-800'],      // Verfügbar - Green
            '#F59E0B' => ['bg-amber-100', 'text-amber-800'],      // Im Einsatz - Amber
            '#3B82F6' => ['bg-blue-100', 'text-blue-800'],        // In Aufbereitung - Blue
            '#EF4444' => ['bg-red-100', 'text-red-800'],          // Defekt - Red
            '#F97316' => ['bg-orange-100', 'text-orange-800'],    // In Reparatur - Orange
            '#6B7280' => ['bg-gray-100', 'text-gray-800'],        // Außer Betrieb - Gray
            default => ['bg-gray-100', 'text-gray-800'],          // Unbekannt - Gray
        };
    }

    /**
     * Get background CSS class for status badge
     */
    public function getBgClassAttribute(): string
    {
        return $this->badge_classes[0];
    }

    /**
     * Get text CSS class for status badge
     */
    public function getTextClassAttribute(): string
    {
        return $this->badge_classes[1];
    }
}
