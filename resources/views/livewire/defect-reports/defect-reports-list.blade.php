<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Defektmeldungen</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $reports->total() }} Meldungen gefunden</p>
        </div>
        <a href="{{ route('defect-reports.create') }}" 
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Neue Meldung
        </a>
    </div>

    <!-- Filter -->
    <div class="dashboard-card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <input type="text" 
                       wire:model.live="search" 
                       placeholder="Suchen..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <select wire:model.live="statusFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alle Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">
                            @switch($status)
                                @case('reported') Gemeldet @break
                                @case('acknowledged') Bestätigt @break
                                @case('in_review') In Bearbeitung @break
                                @case('ordered') Bestellt @break
                                @case('received') Erhalten @break
                                @case('repaired') Repariert @break
                                @case('closed') Abgeschlossen @break
                                @default {{ $status }}
                            @endswitch
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="severityFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alle Schweregrade</option>
                    @foreach($severities as $severity)
                        <option value="{{ $severity }}">
                            @switch($severity)
                                @case('low') Niedrig @break
                                @case('medium') Mittel @break
                                @case('high') Hoch @break
                                @case('critical') Kritisch @break
                                @default {{ $severity }}
                            @endswitch
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="departmentFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alle Abteilungen</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mt-4 flex justify-between items-center">
            <div class="text-sm text-gray-500">
                {{ $reports->count() }} von {{ $reports->total() }} Meldungen angezeigt
            </div>
            <button wire:click="resetFilters" 
                    class="px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                Filter zurücksetzen
            </button>
        </div>
    </div>

    <!-- Defektmeldungen Tabelle -->
    <div class="dashboard-card">
        @if($reports->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Meldung</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Instrument</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Schweregrad</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Status</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Gemeldet von</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Datum</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($reports as $report)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">{{ $report->report_number }}</div>
                                    <div class="text-sm text-gray-600">{{ $report->defect_type_display }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">{{ $report->instrument->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $report->instrument->serial_number }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        @if($report->severity === 'critical') bg-red-100 text-red-800
                                        @elseif($report->severity === 'high') bg-orange-100 text-orange-800
                                        @elseif($report->severity === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ $report->severity_display }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        @if($report->status === 'reported') bg-gray-100 text-gray-800
                                        @elseif($report->status === 'acknowledged') bg-blue-100 text-blue-800
                                        @elseif($report->status === 'in_review') bg-yellow-100 text-yellow-800
                                        @elseif($report->status === 'ordered') bg-purple-100 text-purple-800
                                        @elseif($report->status === 'closed') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $report->status_display }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm text-gray-900">{{ $report->reportedBy->name }}</div>
                                    <div class="text-xs text-gray-600">{{ $report->reportingDepartment->name }}</div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-900">
                                    {{ $report->reported_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('defect-reports.show', $report->id) }}" 
                                           wire:navigate
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            Anzeigen
                                        </a>
                                        
                                        @if($report->status === 'reported' && auth()->user()->role === 'sterilization')
                                            <button wire:click="acknowledgeReport({{ $report->id }})" 
                                                    class="text-green-600 hover:text-green-800 text-sm">
                                                Bestätigen
                                            </button>
                                        @endif
                                        
                                        @if($report->status === 'acknowledged' && auth()->user()->role === 'procurement')
                                            <button wire:click="createPurchaseOrder({{ $report->id }})" 
                                                    class="text-purple-600 hover:text-purple-800 text-sm">
                                                Bestellen
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reports->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">Keine Defektmeldungen gefunden.</p>
            </div>
        @endif
    </div>
</div>
