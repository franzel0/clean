<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstrumentMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'instrument_id',
        'from_department_id',
        'to_department_id',
        'from_container_id',
        'to_container_id',
        'movement_type',
        'status_before',
        'status_after',
        'moved_by',
        'notes',
        'moved_at',
    ];

    protected $casts = [
        'moved_at' => 'datetime',
    ];

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function fromDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'from_department_id');
    }

    public function toDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    public function fromContainer(): BelongsTo
    {
        return $this->belongsTo(Container::class, 'from_container_id');
    }

    public function toContainer(): BelongsTo
    {
        return $this->belongsTo(Container::class, 'to_container_id');
    }

    public function movedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moved_by');
    }

    public function getMovementTypeDisplayAttribute(): string
    {
        return match($this->movement_type) {
            'dispatch' => 'Ausgabe',
            'return' => 'Rückgabe',
            'transfer' => 'Transfer',
            'sterilization' => 'Sterilisation',
            'repair' => 'Reparatur',
            default => ucfirst($this->movement_type),
        };
    }
}
