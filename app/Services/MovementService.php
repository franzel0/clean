<?php

namespace App\Services;

use App\Models\InstrumentMovement;
use App\Models\Instrument;

class MovementService
{
    public static function logMovement(
        Instrument $instrument,
        string $movementType,
        ?int $fromDepartmentId = null,
        ?int $toDepartmentId = null,
        ?int $fromContainerId = null,
        ?int $toContainerId = null,
        ?int $statusBefore = null,
        ?int $statusAfter = null,
        ?string $notes = null,
        ?int $movedBy = null
    ): InstrumentMovement {
        // Use current status_id if not provided
        $statusBefore = $statusBefore ?? $instrument->getOriginal('status_id') ?? $instrument->status_id;
        $statusAfter = $statusAfter ?? $instrument->status_id;
        
        // Use current user if not provided
        $movedBy = $movedBy ?? (\Illuminate\Support\Facades\Auth::id() ?? 1);

        return InstrumentMovement::create([
            'instrument_id' => $instrument->id,
            'from_department_id' => $fromDepartmentId,
            'to_department_id' => $toDepartmentId,
            'from_container_id' => $fromContainerId,
            'to_container_id' => $toContainerId,
            'movement_type' => $movementType,
            'status_before' => $statusBefore,
            'status_after' => $statusAfter,
            'moved_by' => $movedBy,
            'notes' => $notes,
            'moved_at' => now(),
        ]);
    }

    public static function logStatusChange(
        Instrument $instrument,
        int $newStatusId,
        ?string $notes = null,
        ?int $movedBy = null
    ): ?InstrumentMovement {
        $oldStatusId = $instrument->getOriginal('status_id');
        
        // Only log if status actually changed
        if ($oldStatusId === $newStatusId) {
            return null;
        }

        // Get status names for notes
        $oldStatusName = $instrument->getOriginal('status_id') ? \App\Models\InstrumentStatus::find($oldStatusId)?->name : 'Unknown';
        $newStatusName = \App\Models\InstrumentStatus::find($newStatusId)?->name;

        // Determine movement type based on status change
        $movementType = match($newStatusName) {
            'Wartung' => 'repair',
            'Verfügbar' => ($oldStatusName === 'In Benutzung') ? 'return' : 'sterilization',
            'In Benutzung' => 'dispatch',
            default => 'transfer'
        };

        return self::logMovement(
            instrument: $instrument,
            movementType: $movementType,
            statusBefore: $oldStatusId,
            statusAfter: $newStatusId,
            notes: $notes ?? "Status geändert von {$oldStatusName} zu {$newStatusName}",
            movedBy: $movedBy
        );
    }

    public static function logContainerTransfer(
        Instrument $instrument,
        ?int $fromContainerId,
        ?int $toContainerId,
        ?string $notes = null,
        ?int $movedBy = null
    ): InstrumentMovement {
        return self::logMovement(
            instrument: $instrument,
            movementType: 'transfer',
            fromContainerId: $fromContainerId,
            toContainerId: $toContainerId,
            notes: $notes ?? 'Container-Transfer',
            movedBy: $movedBy
        );
    }

    public static function logDepartmentTransfer(
        Instrument $instrument,
        ?int $fromDepartmentId,
        ?int $toDepartmentId,
        ?string $notes = null,
        ?int $movedBy = null
    ): InstrumentMovement {
        return self::logMovement(
            instrument: $instrument,
            movementType: 'transfer',
            fromDepartmentId: $fromDepartmentId,
            toDepartmentId: $toDepartmentId,
            notes: $notes ?? 'Abteilungs-Transfer',
            movedBy: $movedBy
        );
    }
}
