<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    protected $fillable = [
        'name',
        'website',
        'contact_email',
        'contact_phone',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Relationships
    public function instruments(): HasMany
    {
        return $this->hasMany(Instrument::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    // Helper methods
    public function getInstrumentCountAttribute()
    {
        return $this->instruments()->count();
    }

    public function getPurchaseOrderCountAttribute()
    {
        return $this->purchaseOrders()->count();
    }

    public function canBeDeleted(): bool
    {
        return $this->instruments()->count() === 0 && $this->purchaseOrders()->count() === 0;
    }
}
