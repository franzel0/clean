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
        'estimated_cost',
        'actual_cost',
        'status',
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
