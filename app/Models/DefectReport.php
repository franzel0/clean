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
        'instrument_id',
        'defect_type_id',
        'reported_by',
        'reporting_department_id',
        'description',
        'severity',
        'status',
        'reported_at',
        'assigned_to',
        'resolved_at',
        'resolution_notes',
        'repair_cost',
        'photos',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
        'repair_cost' => 'decimal:2',
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

    public function defectType(): BelongsTo
    {
        return $this->belongsTo(DefectType::class, 'defect_type_id');
    }

    public function purchaseOrder(): HasOne
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'offen' => 'Offen',
            'in_bearbeitung' => 'In Bearbeitung',
            'abgeschlossen' => 'Abgeschlossen',
            'abgelehnt' => 'Abgelehnt',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'offen' => 'bg-red-100 text-red-800',
            'in_bearbeitung' => 'bg-blue-100 text-blue-800',
            'abgeschlossen' => 'bg-green-100 text-green-800',
            'abgelehnt' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getSeverityDisplayAttribute(): string
    {
        return match($this->severity) {
            'niedrig' => 'Niedrig',
            'mittel' => 'Mittel',
            'hoch' => 'Hoch',
            'kritisch' => 'Kritisch',
            default => ucfirst($this->severity),
        };
    }

    public function getSeverityBadgeClassAttribute(): string
    {
        return match($this->severity) {
            'niedrig' => 'bg-green-100 text-green-800',
            'mittel' => 'bg-yellow-100 text-yellow-800',
            'hoch' => 'bg-orange-100 text-orange-800',
            'kritisch' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getDefectTypeDisplayAttribute(): string
    {
        // Bevorzuge die DefectType-Beziehung, falls vorhanden
        if ($this->defectType) {
            return $this->defectType->name;
        }
        
        // Fallback auf die String-Eigenschaft
        return match($this->defect_type) {
            'broken' => 'Kaputt',
            'dull' => 'Stumpf',
            'bent' => 'Verbogen',
            'missing_parts' => 'Fehlende Teile',
            'other' => 'Sonstiges',
            default => ucfirst($this->defect_type ?? 'Unbekannt'),
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

        // Auto-generation of report number disabled since the field doesn't exist in the database
        // static::creating(function ($model) {
        //     if (!$model->report_number) {
        //         $model->report_number = 'DR-' . date('Y') . '-' . str_pad(
        //             static::whereYear('created_at', date('Y'))->count() + 1,
        //             6,
        //             '0',
        //             STR_PAD_LEFT
        //         );
        //     }
        // });
    }
}
