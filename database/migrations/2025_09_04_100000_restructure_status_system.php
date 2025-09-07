<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Füge bg_class und text_class Spalten zu instrument_statuses hinzu (falls nicht vorhanden)
        if (!Schema::hasColumn('instrument_statuses', 'bg_class')) {
            Schema::table('instrument_statuses', function (Blueprint $table) {
                $table->string('bg_class')->nullable()->after('color');
                $table->string('text_class')->nullable()->after('bg_class');
            });
        }

        // 2. Lösche alle bestehenden instrument_statuses und füge neue hinzu
        DB::table('instrument_statuses')->delete();
        DB::table('instrument_statuses')->insert([
            ['name' => 'Verfügbar', 'color' => '#059669', 'bg_class' => 'bg-green-100', 'text_class' => 'text-green-800', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'In Betrieb', 'color' => '#2563eb', 'bg_class' => 'bg-blue-100', 'text_class' => 'text-blue-800', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Defekt gemeldet', 'color' => '#ea580c', 'bg_class' => 'bg-orange-100', 'text_class' => 'text-orange-800', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Defekt bestätigt', 'color' => '#dc2626', 'bg_class' => 'bg-red-100', 'text_class' => 'text-red-800', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ersatz bestellt', 'color' => '#d97706', 'bg_class' => 'bg-yellow-100', 'text_class' => 'text-yellow-800', 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ersatz geliefert', 'color' => '#7c3aed', 'bg_class' => 'bg-purple-100', 'text_class' => 'text-purple-800', 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'In Reparatur', 'color' => '#f59e0b', 'bg_class' => 'bg-amber-100', 'text_class' => 'text-amber-800', 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Repariert', 'color' => '#059669', 'bg_class' => 'bg-teal-100', 'text_class' => 'text-teal-800', 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aussortiert', 'color' => '#6b7280', 'bg_class' => 'bg-gray-100', 'text_class' => 'text-gray-800', 'sort_order' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Verloren/Vermisst', 'color' => '#991b1b', 'bg_class' => 'bg-red-200', 'text_class' => 'text-red-900', 'sort_order' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'In Wartung', 'color' => '#4338ca', 'bg_class' => 'bg-indigo-100', 'text_class' => 'text-indigo-800', 'sort_order' => 11, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Prüfe ob container_statuses die neuen Spalten hat, falls nicht füge sie hinzu
        if (!Schema::hasColumn('container_statuses', 'bg_class')) {
            Schema::table('container_statuses', function (Blueprint $table) {
                $table->string('bg_class')->nullable()->after('color');
                $table->string('text_class')->nullable()->after('bg_class');
            });
        }

        // 4. Aktualisiere container_statuses mit fokussierten Status-Werten
        DB::table('container_statuses')->delete();
        DB::table('container_statuses')->insert([
            ['name' => 'Vollständig & betriebsbereit', 'color' => '#059669', 'bg_class' => 'bg-green-100', 'text_class' => 'text-green-800', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Unvollständig aber betriebsbereit', 'color' => '#d97706', 'bg_class' => 'bg-yellow-100', 'text_class' => 'text-yellow-800', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nicht betriebsbereit', 'color' => '#dc2626', 'bg_class' => 'bg-red-100', 'text_class' => 'text-red-800', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'In Wartung', 'color' => '#2563eb', 'bg_class' => 'bg-blue-100', 'text_class' => 'text-blue-800', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Außer Betrieb', 'color' => '#6b7280', 'bg_class' => 'bg-gray-100', 'text_class' => 'text-gray-800', 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Entferne status_id aus defect_reports (Status wird über Instrument verwaltet)
        if (Schema::hasColumn('defect_reports', 'status_id')) {
            Schema::table('defect_reports', function (Blueprint $table) {
                $table->dropForeign(['status_id']);
                $table->dropColumn('status_id');
            });
        }

        // 4. Entferne status_id aus purchase_orders (Status wird über Instrument verwaltet)
        if (Schema::hasColumn('purchase_orders', 'status_id')) {
            // Lösche zuerst den Index der status_id enthält
            DB::statement('DROP INDEX IF EXISTS purchase_orders_status_id_order_date_index');
            
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->dropForeign(['status_id']);
                $table->dropColumn('status_id');
            });
        }

        // 5. Füge Workflow-Felder zu defect_reports hinzu (falls noch nicht vorhanden)
        Schema::table('defect_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('defect_reports', 'is_resolved')) {
                $table->boolean('is_resolved')->default(false)->after('severity');
            }
            if (!Schema::hasColumn('defect_reports', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('is_resolved');
            }
            if (!Schema::hasColumn('defect_reports', 'resolved_by')) {
                $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null')->after('resolved_at');
            }
            if (!Schema::hasColumn('defect_reports', 'resolution_notes')) {
                $table->text('resolution_notes')->nullable()->after('resolved_by');
            }
        });

        // 6. Füge Workflow-Felder zu purchase_orders hinzu (falls noch nicht vorhanden)
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'is_delivered')) {
                $table->boolean('is_delivered')->default(false)->after('total_amount');
            }
            if (!Schema::hasColumn('purchase_orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('is_delivered');
            }
            if (!Schema::hasColumn('purchase_orders', 'received_by')) {
                $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null')->after('delivered_at');
            }
            if (!Schema::hasColumn('purchase_orders', 'delivery_notes')) {
                $table->text('delivery_notes')->nullable()->after('received_by');
            }
        });
    }

    public function down(): void
    {
        // Rückgängig machen der Änderungen
        
        // Entferne neue Felder aus purchase_orders
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_orders', 'received_by')) {
                $table->dropForeign(['received_by']);
            }
            $table->dropColumn(['is_delivered', 'delivered_at', 'received_by', 'delivery_notes']);
        });

        // Entferne neue Felder aus defect_reports
        Schema::table('defect_reports', function (Blueprint $table) {
            if (Schema::hasColumn('defect_reports', 'resolved_by')) {
                $table->dropForeign(['resolved_by']);
            }
            $table->dropColumn(['is_resolved', 'resolved_at', 'resolved_by', 'resolution_notes']);
        });

        // Füge status_id wieder zu defect_reports hinzu  
        if (Schema::hasTable('defect_statuses')) {
            Schema::table('defect_reports', function (Blueprint $table) {
                $table->foreignId('status_id')->nullable()->constrained('defect_statuses')->onDelete('set null');
            });
        }

        // Entferne bg_class und text_class aus container_statuses
        if (Schema::hasColumn('container_statuses', 'bg_class')) {
            Schema::table('container_statuses', function (Blueprint $table) {
                $table->dropColumn(['bg_class', 'text_class']);
            });
        }

        // Entferne bg_class und text_class aus instrument_statuses
        if (Schema::hasColumn('instrument_statuses', 'bg_class')) {
            Schema::table('instrument_statuses', function (Blueprint $table) {
                $table->dropColumn(['bg_class', 'text_class']);
            });
        }
    }
};
