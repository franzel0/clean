<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class DefectReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number',
        'instrument_id',
        'reported_by',
        'reporting_department_id',
        'operating_room_id',
        'defect_type',
        'description',
        'severity',
        'status',
        'reported_at',
        'acknowledged_at',
        'acknowledged_by',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
        'photos',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
        'photos' => 'array',
    ];

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function reportingDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'reporting_department_id');
    }

    public function operatingRoom(): BelongsTo
    {
        return $this->belongsTo(OperatingRoom::class);
    }

    public function purchaseOrder(): HasOne
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'reported' => 'Gemeldet',
            'acknowledged' => 'Bestätigt',
            'in_review' => 'In Bearbeitung',
            'ordered' => 'Bestellt',
            'received' => 'Erhalten',
            'repaired' => 'Repariert',
            'closed' => 'Abgeschlossen',
            default => ucfirst($this->status),
        };
    }

    public function getDefectTypeDisplayAttribute(): string
    {
        return match($this->defect_type) {
            'broken' => 'Kaputt',
            'dull' => 'Stumpf',
            'bent' => 'Verbogen',
            'missing_parts' => 'Fehlende Teile',
            'other' => 'Sonstiges',
            default => ucfirst($this->defect_type),
        };
    }

    public function getSeverityDisplayAttribute(): string
    {
        return match($this->severity) {
            'low' => 'Niedrig',
            'medium' => 'Mittel',
            'high' => 'Hoch',
            'critical' => 'Kritisch',
            default => ucfirst($this->severity),
        };
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', ['closed']);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('reporting_department_id', $departmentId);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->report_number) {
                $model->report_number = 'DR-' . date('Y') . '-' . str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}
