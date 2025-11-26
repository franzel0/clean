<div class="container mx-auto px-4 py-8">
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Bestellungen</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $orders->total() }} Bestellungen gefunden</p>
            </div>
            <a href="{{ route('purchase-orders.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Neue Bestellung
            </a>
        </div>
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
                <select wire:model.live="departmentFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alle Abteilungen</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="completionFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alle Bestellungen</option>
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

    <!-- Bestellungen Tabelle -->
    <div class="dashboard-card">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'order_number' ? 'bg-blue-100' : 'bg-gray-50' }}" wire:click="sort('order_number')">
                                {{ __('messages.order_number') }}
                                @if($sortBy === 'order_number')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'defect_report_id' ? 'bg-blue-100' : 'bg-gray-50' }}" wire:click="sort('defect_report_id')">
                                {{ __('messages.instrument') }}
                                @if($sortBy === 'defect_report_id')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 bg-gray-50">Instrumentenstatus</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 bg-gray-50">Defektmeldung</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'is_completed' ? 'bg-blue-100' : 'bg-gray-50' }}" wire:click="sort('is_completed')">
                                Abgeschlossen
                                @if($sortBy === 'is_completed')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'requested_by' ? 'bg-blue-100' : 'bg-gray-50' }}" wire:click="sort('requested_by')">
                                {{ __('messages.requested_by') }}
                                @if($sortBy === 'requested_by')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-200 {{ $sortBy === 'order_date' ? 'bg-blue-100' : 'bg-gray-50' }}" wire:click="sort('order_date')">
                                {{ __('messages.date') }}
                                @if($sortBy === 'order_date')
                                    <span class="text-blue-600 font-bold">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 bg-gray-50">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">{{ $order->order_number }}</div>
                                    @if($order->manufacturer_id)
                                        <div class="text-sm text-gray-600">{{ $order->manufacturer_display }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($order->defectReport && $order->defectReport->instrument)
                                        <div class="font-medium text-gray-900">{{ $order->defectReport->instrument->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $order->defectReport->instrument->serial_number }}</div>
                                    @else
                                        <span class="text-gray-500">Kein Instrument</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($order->defectReport && $order->defectReport->instrument && $order->defectReport->instrument->instrumentStatus)
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $order->defectReport->instrument->instrumentStatus->bg_class }} {{ $order->defectReport->instrument->instrumentStatus->text_class }}">
                                            {{ $order->defectReport->instrument->instrumentStatus->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($order->defectReport)
                                        <a href="{{ route('defect-reports.show', $order->defectReport->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            DR-{{ date('Y', strtotime($order->defectReport->created_at)) }}-{{ str_pad($order->defectReport->id, 6, '0', STR_PAD_LEFT) }}
                                        </a>
                                        <div class="text-sm text-gray-600">{{ $order->defectReport->defect_type_display }}</div>
                                    @else
                                        <span class="text-gray-500 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($order->is_completed)
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
                                    <div class="text-sm text-gray-900">{{ $order->requestedBy->name }}</div>
                                    @if($order->defectReport && $order->defectReport->reportingDepartment)
                                        <div class="text-xs text-gray-600">{{ $order->defectReport->reportingDepartment->name }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-900">
                                    {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d.m.Y') : 'Nicht gesetzt' }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('purchase-orders.show', $order) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            Anzeigen
                                        </a>
                                        <button wire:click="downloadPdf({{ $order->id }})"
                                                class="text-red-600 hover:text-red-800 text-sm">
                                            PDF
                                        </button>
                                        
                                        @if(is_null($order->received_at) && auth()->user()->role === 'procurement')
                                            <button wire:click="markAsReceived({{ $order->id }})" 
                                                    class="text-green-600 hover:text-green-800 text-sm">
                                                Als erhalten markieren
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
                        Zeige {{ ($orders->currentPage() - 1) * 20 + 1 }} bis {{ min($orders->currentPage() * 20, $orders->total()) }} von {{ $orders->total() }} Bestellungen
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if ($orders->onFirstPage())
                            <button disabled class="px-3 py-2 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                                ← Zurück
                            </button>
                        @else
                            <button wire:click="gotoPage({{ $orders->currentPage() - 1 }})" class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                ← Zurück
                            </button>
                        @endif

                        <div class="flex gap-1">
                            @for ($i = 1; $i <= $orders->lastPage(); $i++)
                                @if ($i >= $orders->currentPage() - 2 && $i <= $orders->currentPage() + 2)
                                    @if ($i === $orders->currentPage())
                                        <button class="px-3 py-2 bg-blue-600 text-white rounded-md">{{ $i }}</button>
                                    @else
                                        <button wire:click="gotoPage({{ $i }})" class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">{{ $i }}</button>
                                    @endif
                                @elseif (($i === $orders->currentPage() - 3 || $i === $orders->currentPage() + 3) && $orders->lastPage() > 5)
                                    <span class="px-2 py-2">...</span>
                                @endif
                            @endfor
                        </div>

                        @if ($orders->hasMorePages())
                            <button wire:click="gotoPage({{ $orders->currentPage() + 1 }})" class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
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
                <p class="text-gray-500">Keine Bestellungen gefunden.</p>
            </div>
        @endif
    </div>
</div>
