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
        
        // Ensure status values are numeric IDs
        if (!is_numeric($statusBefore)) {
            $statusBefore = self::convertStatusNameToId($statusBefore);
        }
        if (!is_numeric($statusAfter)) {
            $statusAfter = self::convertStatusNameToId($statusAfter);
        }
        
        // Use current user if not provided
        $movedBy = $movedBy ?? (\Illuminate\Support\Facades\Auth::id() ?? 1);

        $movement = InstrumentMovement::create([
            'instrument_id' => $instrument->id,
            'movement_type' => $movementType,
            'from_container_id' => $fromContainerId,
            'to_container_id' => $toContainerId,
            'from_status' => $statusBefore,
            'to_status' => $statusAfter,
            'performed_by' => $movedBy,
            'notes' => $notes,
            'performed_at' => now(),
        ]);

        // Update instrument's current status to the new status
        if ($statusAfter && is_numeric($statusAfter)) {
            $instrument->update(['status_id' => $statusAfter]);
        }

        return $movement;
    }

    /**
     * Log movement without updating the instrument (for manual updates)
     */
    public static function logMovementOnly(
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
        
        // Ensure status values are numeric IDs
        if (!is_numeric($statusBefore)) {
            $statusBefore = self::convertStatusNameToId($statusBefore);
        }
        if (!is_numeric($statusAfter)) {
            $statusAfter = self::convertStatusNameToId($statusAfter);
        }
        
        // Use current user if not provided
        $movedBy = $movedBy ?? (\Illuminate\Support\Facades\Auth::id() ?? 1);

        return InstrumentMovement::create([
            'instrument_id' => $instrument->id,
            'movement_type' => $movementType,
            'from_container_id' => $fromContainerId,
            'to_container_id' => $toContainerId,
            'from_status' => $statusBefore,
            'to_status' => $statusAfter,
            'performed_by' => $movedBy,
            'notes' => $notes,
            'performed_at' => now(),
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
            'In Wartung' => 'maintenance',
            'Wartung' => 'maintenance',
            default => 'status_change'
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

    /**
     * Convert status name to numeric ID
     */
    private static function convertStatusNameToId($statusName): ?int
    {
        // Falls es bereits eine ID ist, zurückgeben
        if (is_numeric($statusName)) {
            return (int)$statusName;
        }

        // Status dynamisch aus der Datenbank laden
        $status = \App\Models\InstrumentStatus::where('name', $statusName)->first();
        
        if ($status) {
            return $status->id;
        }

        // Falls Status nicht gefunden: Warnung loggen und Fallback verwenden
        \Log::warning("MovementService: Unbekannter Status-Name '$statusName' verwendet. Fallback zu 'Verfügbar'.");
        
        // Als Fallback: "Verfügbar" Status
        $fallbackStatus = \App\Models\InstrumentStatus::where('name', 'Verfügbar')->first();
        return $fallbackStatus?->id ?? 1; // Notfall-Fallback zu ID 1
    }
}
