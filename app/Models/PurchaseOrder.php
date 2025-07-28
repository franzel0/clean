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
        'defect_report_id',
        'requested_by',
        'supplier',
        'supplier_id',
        'manufacturer_id',
        'estimated_cost',
        'actual_cost',
        'status',
        'status_id',
        'requested_at',
        'approved_at',
        'approved_by',
        'ordered_at',
        'expected_delivery',
        'received_at',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'ordered_at' => 'datetime',
        'expected_delivery' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function defectReport(): BelongsTo
    {
        return $this->belongsTo(DefectReport::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
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

    public function manufacturerRelation(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'requested' => 'Angefordert',
            'approved' => 'Genehmigt',
            'ordered' => 'Bestellt',
            'shipped' => 'Versandt',
            'received' => 'Erhalten',
            'completed' => 'Abgeschlossen',
            'cancelled' => 'Storniert',
            default => ucfirst($this->status),
        };
    }

    public function getSupplierDisplayAttribute(): string
    {
        // Wenn supplier_id vorhanden ist und Beziehung geladen ist
        if ($this->supplier_id && $this->relationLoaded('supplier') && $this->supplier && is_object($this->supplier)) {
            $display = $this->supplier->name;
            if ($this->supplier->contact_person) {
                $display .= ' - ' . $this->supplier->contact_person;
            }
            return $display;
        }
        
        // Fallback: Versuche supplier_id zu laden falls nicht geladen
        if ($this->supplier_id && !$this->relationLoaded('supplier')) {
            try {
                $supplier = $this->supplier;
                if ($supplier) {
                    $display = $supplier->name;
                    if ($supplier->contact_person) {
                        $display .= ' - ' . $supplier->contact_person;
                    }
                    return $display;
                }
            } catch (\Exception $e) {
                // Falls das fehlschlägt, fallen wir auf das String-Feld zurück
            }
        }
        
        // Fallback auf das alte String-Feld
        if ($this->attributes['supplier'] && is_string($this->attributes['supplier'])) {
            return $this->attributes['supplier'];
        }
        
        return 'Kein Lieferant angegeben';
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
