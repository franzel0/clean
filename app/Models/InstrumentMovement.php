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

    public function statusBeforeObject(): BelongsTo
    {
        return $this->belongsTo(InstrumentStatus::class, 'status_before');
    }

    public function statusAfterObject(): BelongsTo
    {
        return $this->belongsTo(InstrumentStatus::class, 'status_after');
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

    public function getStatusBeforeDisplayAttribute(): string
    {
        return $this->convertStatusToDisplay($this->status_before);
    }

    public function getStatusAfterDisplayAttribute(): string
    {
        return $this->convertStatusToDisplay($this->status_after);
    }

    private function convertStatusToDisplay($status): string
    {
        // Wenn leer oder null, zeige "Unbekannt"
        if (empty($status)) {
            return 'Unbekannt';
        }

        // Wenn es eine Zahl ist, versuche es als InstrumentStatus ID zu interpretieren
        if (is_numeric($status)) {
            // Verwende die geladene Relation wenn verfügbar
            if ($status == $this->status_before && $this->relationLoaded('statusBeforeObject')) {
                return $this->statusBeforeObject?->name ?? "Status ID {$status}";
            }
            if ($status == $this->status_after && $this->relationLoaded('statusAfterObject')) {
                return $this->statusAfterObject?->name ?? "Status ID {$status}";
            }
            
            // Fallback: Lade aus der Datenbank
            $instrumentStatus = InstrumentStatus::find($status);
            return $instrumentStatus?->name ?? "Status ID {$status}";
        }

        // Erweiterte Mapping-Tabelle für englische Begriffe
        $statusMap = [
            'available' => 'Verfügbar',
            'in_use' => 'In Verwendung', 
            'dirty' => 'Verschmutzt',
            'clean' => 'Sauber',
            'in_sterilization' => 'In Sterilisation',
            'sterile' => 'Steril',
            'defective' => 'Defekt',
            'in_repair' => 'In Reparatur',
            'out_of_service' => 'Außer Betrieb',
            'lost' => 'Verloren/Vermisst',
            'disposed' => 'Aussortiert',
            'maintenance' => 'Wartung',
            'broken' => 'Defekt',
            'repaired' => 'Repariert',
            'returned' => 'Zurückgegeben',
            'dispatched' => 'Ausgegeben',
            'transferred' => 'Transferiert',
            'repair' => 'Reparatur',
            'missing' => 'Vermisst',
            'reserved' => 'Reserviert',
        ];

        // Direkte Übersetzung wenn verfügbar
        $lowerStatus = strtolower($status);
        if (isset($statusMap[$lowerStatus])) {
            return $statusMap[$lowerStatus];
        }

        // Fallback: Ersten Buchstaben groß schreiben und Unterstriche ersetzen
        return ucfirst(str_replace('_', ' ', $status ?? 'Unbekannt'));
    }
}
