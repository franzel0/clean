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
        ?string $statusBefore = null,
        ?string $statusAfter = null,
        ?string $notes = null,
        ?int $movedBy = null
    ): InstrumentMovement {
        // Use current status if not provided
        $statusBefore = $statusBefore ?? $instrument->getOriginal('status') ?? $instrument->status;
        $statusAfter = $statusAfter ?? $instrument->status;
        
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
        string $newStatus,
        ?string $notes = null,
        ?int $movedBy = null
    ): ?InstrumentMovement {
        $oldStatus = $instrument->getOriginal('status');
        
        // Only log if status actually changed
        if ($oldStatus === $newStatus) {
            return null;
        }

        // Determine movement type based on status change
        $movementType = match($newStatus) {
            'in_repair' => 'repair',
            'available' => $oldStatus === 'in_use' ? 'return' : 'sterilization',
            'in_use' => 'dispatch',
            default => 'transfer'
        };

        return self::logMovement(
            instrument: $instrument,
            movementType: $movementType,
            statusBefore: $oldStatus,
            statusAfter: $newStatus,
            notes: $notes ?? "Status geändert von {$oldStatus} zu {$newStatus}",
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
