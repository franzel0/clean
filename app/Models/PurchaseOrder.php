<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'supplier_id',
        'manufacturer_id',
        'defect_report_id',
        'ordered_by',
        'approved_by',
        'received_by',
        'order_date',
        'approved_at',
        'ordered_at',
        'expected_delivery',
        'delivery_date',
        'total_amount',
        'notes',
        'received_at',
        'is_completed',
        'is_delivered',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'order_date' => 'date',
        'approved_at' => 'datetime',
        'ordered_at' => 'datetime',
        'expected_delivery' => 'date',
        'delivery_date' => 'date',
        'received_at' => 'datetime',
        'is_completed' => 'boolean',
        'is_delivered' => 'boolean',
    ];

    public function defectReport(): BelongsTo
    {
        return $this->belongsTo(DefectReport::class);
    }

    public function orderedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }

    // Legacy alias for backward compatibility
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function getManufacturerDisplayAttribute(): string
    {
        // Wenn manufacturer_id vorhanden ist und Beziehung geladen ist
        if ($this->manufacturer_id && $this->relationLoaded('manufacturer') && $this->manufacturer && is_object($this->manufacturer)) {
            $display = $this->manufacturer->name;
            if ($this->manufacturer->contact_person) {
                $display .= ' - ' . $this->manufacturer->contact_person;
            }
            return $display;
        }
        
        // Fallback: Versuche manufacturer_id zu laden falls nicht geladen
        if ($this->manufacturer_id && !$this->relationLoaded('manufacturer')) {
            try {
                $manufacturer = $this->manufacturer;
                if ($manufacturer) {
                    $display = $manufacturer->name;
                    if ($manufacturer->contact_person) {
                        $display .= ' - ' . $manufacturer->contact_person;
                    }
                    return $display;
                }
            } catch (\Exception $e) {
                // Falls das fehlschlägt, Default-Text zurückgeben
            }
        }
        
        return 'Kein Hersteller angegeben';
    }

    public function scopeOpen($query)
    {
        return $query->where('is_completed', false);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->order_number) {
                $model->order_number = 'PO-' . date('Y') . '-' . str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}
