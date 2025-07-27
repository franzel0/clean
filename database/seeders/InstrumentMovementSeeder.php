<?php

namespace Database\Seeders;

use App\Models\InstrumentMovement;
use App\Models\Instrument;
use App\Models\Department;
use App\Models\Container;
use App\Models\User;
use Illuminate\Database\Seeder;

class InstrumentMovementSeeder extends Seeder
{
    public function run(): void
    {
        $instruments = Instrument::all();
        $departments = Department::all();
        $containers = Container::all();
        $users = User::all();

        if ($instruments->isEmpty() || $departments->isEmpty() || $users->isEmpty()) {
            return;
        }

        $movementTypes = ['dispatch', 'return', 'transfer', 'sterilization', 'repair'];
        $statuses = ['available', 'in_use', 'defective', 'in_repair', 'out_of_service'];

        foreach ($instruments->take(20) as $instrument) {
            // Erstelle 3-8 Bewegungen pro Instrument
            $movementCount = rand(3, 8);
            
            for ($i = 0; $i < $movementCount; $i++) {
                $movementType = $movementTypes[array_rand($movementTypes)];
                $statusBefore = $statuses[array_rand($statuses)];
                $statusAfter = $statuses[array_rand($statuses)];
                
                // Ensure status changes make sense
                if ($movementType === 'repair') {
                    $statusBefore = 'defective';
                    $statusAfter = rand(0, 1) ? 'in_repair' : 'available';
                } elseif ($movementType === 'sterilization') {
                    $statusBefore = 'in_use';
                    $statusAfter = 'available';
                } elseif ($movementType === 'dispatch') {
                    $statusBefore = 'available';
                    $statusAfter = 'in_use';
                } elseif ($movementType === 'return') {
                    $statusBefore = 'in_use';
                    $statusAfter = 'available';
                }

                $fromDepartment = $departments->random();
                $toDepartment = $departments->random();
                
                // Ensure different departments for transfers
                if ($movementType === 'transfer' && $fromDepartment->id === $toDepartment->id) {
                    $toDepartment = $departments->where('id', '!=', $fromDepartment->id)->random();
                }

                $fromContainer = $containers->random();
                $toContainer = $containers->random();

                // Generate realistic notes based on movement type
                $notes = match($movementType) {
                    'dispatch' => [
                        'Ausgabe für OP-Saal 1',
                        'Für Notfall-Operation angefordert',
                        'Routineausgabe für geplanten Eingriff',
                        'Ausgabe für ambulanten Eingriff'
                    ],
                    'return' => [
                        'Rückgabe nach OP-Ende',
                        'Operation abgeschlossen',
                        'Instrument nicht benötigt',
                        'Routinerückgabe'
                    ],
                    'transfer' => [
                        'Transfer zur anderen Abteilung',
                        'Abteilungswechsel aufgrund Bedarf',
                        'Umverteilung der Instrumente',
                        'Organisatorischer Transfer'
                    ],
                    'sterilization' => [
                        'Sterilisation nach Verwendung',
                        'Routinesterilisation',
                        'Aufbereitung vor nächstem Einsatz',
                        'Sterilisation gemäß Protokoll'
                    ],
                    'repair' => [
                        'Defekt an Schneide festgestellt',
                        'Mechanismus funktioniert nicht korrekt',
                        'Reparatur der Gelenkverbindung',
                        'Austausch defekter Komponenten',
                        'Schärfung der Klinge erforderlich'
                    ],
                    default => 'Bewegung dokumentiert'
                };

                InstrumentMovement::create([
                    'instrument_id' => $instrument->id,
                    'from_department_id' => $movementType === 'transfer' ? $fromDepartment->id : null,
                    'to_department_id' => in_array($movementType, ['dispatch', 'transfer']) ? $toDepartment->id : null,
                    'from_container_id' => rand(0, 1) ? $fromContainer->id : null,
                    'to_container_id' => rand(0, 1) ? $toContainer->id : null,
                    'movement_type' => $movementType,
                    'status_before' => $statusBefore,
                    'status_after' => $statusAfter,
                    'moved_by' => $users->random()->id,
                    'notes' => $notes[array_rand($notes)],
                    'moved_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                ]);
            }
        }
    }
}
