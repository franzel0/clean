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
        'status_id',
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
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'order_date' => 'date',
        'approved_at' => 'datetime',
        'ordered_at' => 'datetime',
        'expected_delivery' => 'date',
        'delivery_date' => 'date',
        'received_at' => 'datetime',
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

    public function purchaseOrderStatus(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderStatus::class, 'status_id');
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function getStatusDisplayAttribute(): string
    {
        if ($this->purchaseOrderStatus) {
            return $this->purchaseOrderStatus->name;
        }
        
        // Fallback for backward compatibility
        return match($this->status ?? 'unknown') {
            'requested' => 'Angefordert',
            'approved' => 'Genehmigt',
            'ordered' => 'Bestellt',
            'shipped' => 'Versandt',
            'received' => 'Erhalten',
            'completed' => 'Abgeschlossen',
            'cancelled' => 'Storniert',
            default => 'Unbekannt',
        };
    }

    public function getStatusAttribute(): ?string
    {
        // If we have a status_id, map it to a status string for backward compatibility
        if ($this->status_id && $this->purchaseOrderStatus) {
            return match($this->purchaseOrderStatus->name) {
                'Entwurf' => 'requested',
                'Freigegeben' => 'approved',
                'Bestellt' => 'ordered',
                'Versandt' => 'shipped',
                'Geliefert' => 'received',
                'Abgeschlossen' => 'completed',
                'Storniert' => 'cancelled',
                default => 'unknown',
            };
        }
        
        return null;
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
        return $query->whereNotIn('status', ['received', 'completed', 'cancelled']);
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
