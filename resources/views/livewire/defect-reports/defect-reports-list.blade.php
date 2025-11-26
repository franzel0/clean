<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.defect_reports') }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $reports->total() }} {{ __('messages.defect_reports_found') }}</p>
        </div>
        <a href="{{ route('defect-reports.create') }}" 
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            {{ __('messages.create_defect_report') }}
        </a>
    </div>

    <!-- Filter -->
    <div class="dashboard-card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <input type="text" 
                       wire:model.live="search" 
                       placeholder="{{ __('messages.search') }}..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <select wire:model.live="severityFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('messages.all_severities') }}</option>
                    @foreach($severities as $severity)
                        <option value="{{ $severity }}">
                            @switch($severity)
                                @case('niedrig') Niedrig @break
                                @case('mittel') Mittel @break
                                @case('hoch') Hoch @break
                                @case('kritisch') Kritisch @break
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

            <div>
                <select wire:model.live="statusFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alle Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="completionFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alle Meldungen</option>
                    <option value="active">Nur aktive</option>
                    <option value="completed">Nur abgeschlossene</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <button wire:click="resetFilters" 
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                    Filter zurücksetzen
                </button>
            </div>
        </div>
    </div>

    <!-- Defektmeldungen Tabelle -->
    <div class="dashboard-card">
        @if($reports->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'report_number' ? 'bg-blue-50' : '' }}" wire:click="sort('report_number')">
                                {{ __('messages.report_number') }} 
                                @if($sortBy === 'report_number')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'instrument_id' ? 'bg-blue-50' : '' }}" wire:click="sort('instrument_id')">
                                {{ __('messages.instruments') }}
                                @if($sortBy === 'instrument_id')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'instrument_id' ? 'bg-blue-50' : '' }}" wire:click="sort('instrument_id')">
                                Instrumentenstatus
                                @if($sortBy === 'instrument_id')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'severity' ? 'bg-blue-50' : '' }}" wire:click="sort('severity')">
                                {{ __('messages.severity') }}
                                @if($sortBy === 'severity')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'acknowledged_by' ? 'bg-blue-50' : '' }}" wire:click="sort('acknowledged_by')">
                                {{ __('messages.reported_by') }}
                                @if($sortBy === 'acknowledged_by')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'reported_at' ? 'bg-blue-50' : '' }}" wire:click="sort('reported_at')">
                                {{ __('messages.date') }}
                                @if($sortBy === 'reported_at')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'is_completed' ? 'bg-blue-50' : '' }}" wire:click="sort('is_completed')">
                                Status
                                @if($sortBy === 'is_completed')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($reports as $report)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-blue-600">DR-{{ date('Y', strtotime($report->created_at)) }}-{{ str_pad($report->id, 6, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">{{ $report->instrument->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $report->instrument->serial_number }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($report->instrument && $report->instrument->instrumentStatus)
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $report->instrument->instrumentStatus->bg_class }} {{ $report->instrument->instrumentStatus->text_class }}">
                                            {{ $report->instrument->instrumentStatus->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $report->severity_badge_class }}">
                                        {{ $report->severity_display }}
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
                                    @if($report->is_completed)
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            Abgeschlossen
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                            Aktiv
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('defect-reports.show', $report->id) }}" 
                                           wire:navigate
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            {{ __('messages.view') }}
                                        </a>
                                        
                                        @if($report->status === 'reported' && auth()->user()->role === 'sterilization')
                                            <button wire:click="acknowledgeReport({{ $report->id }})" 
                                                    class="text-green-600 hover:text-green-800 text-sm">
                                                {{ __('messages.confirm') }}
                                            </button>
                                        @endif
                                        
                                        @if($report->status === 'acknowledged' && auth()->user()->role === 'procurement')
                                            <button wire:click="createPurchaseOrder({{ $report->id }})" 
                                                    class="text-purple-600 hover:text-purple-800 text-sm">
                                                {{ __('messages.order') }}
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
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        Zeige {{ ($reports->currentPage() - 1) * 20 + 1 }} bis {{ min($reports->currentPage() * 20, $reports->total()) }} von {{ $reports->total() }} Meldungen
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if ($reports->onFirstPage())
                            <button disabled class="px-3 py-2 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                                ← Zurück
                            </button>
                        @else
                            <button wire:click="gotoPage({{ $reports->currentPage() - 1 }})" class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                ← Zurück
                            </button>
                        @endif

                        <div class="flex gap-1">
                            @for ($i = 1; $i <= $reports->lastPage(); $i++)
                                @if ($i >= $reports->currentPage() - 2 && $i <= $reports->currentPage() + 2)
                                    @if ($i === $reports->currentPage())
                                        <button class="px-3 py-2 bg-blue-600 text-white rounded-md">{{ $i }}</button>
                                    @else
                                        <button wire:click="gotoPage({{ $i }})" class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">{{ $i }}</button>
                                    @endif
                                @elseif (($i === $reports->currentPage() - 3 || $i === $reports->currentPage() + 3) && $reports->lastPage() > 5)
                                    <span class="px-2 py-2">...</span>
                                @endif
                            @endfor
                        </div>

                        @if ($reports->hasMorePages())
                            <button wire:click="gotoPage({{ $reports->currentPage() + 1 }})" class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Weiter →
                            </button>
                        @else
                            <button disabled class="px-3 py-2 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                                Weiter →
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">Keine Defektmeldungen gefunden.</p>
            </div>
        @endif
    </div>
</div>
