<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Bestellungen</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Verwalten Sie Ersatzbestellungen für defekte Instrumente
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('purchase-orders.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Neue Bestellung
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="container mx-auto px-4 py-8">
    <!-- Status-Info -->
    <div class="mb-6">
        <p class="text-sm text-gray-600">{{ $orders->total() }} Bestellungen gefunden</p>
    </div>

    <!-- Filter -->
    <div class="dashboard-card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
                                @case('requested') Angefordert @break
                                @case('approved') Genehmigt @break
                                @case('ordered') Bestellt @break
                                @case('received') Erhalten @break
                                @case('completed') Abgeschlossen @break
                                @default {{ $status }}
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
                {{ $orders->count() }} von {{ $orders->total() }} Bestellungen angezeigt
            </div>
            <button wire:click="resetFilters" 
                    class="px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                Filter zurücksetzen
            </button>
        </div>
    </div>

    <!-- Bestellungen Tabelle -->
    <div class="dashboard-card">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">{{ __('messages.order_number') }}</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">{{ __('messages.instrument') }}</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">{{ __('messages.report') }}</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">{{ __('messages.status') }}</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">{{ __('messages.requested_by') }}</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">{{ __('messages.date') }}</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">{{ __('messages.actions') }}</th>
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
                                    @if($order->defectReport)
                                        <div class="text-sm text-gray-900">{{ $order->defectReport->report_number }}</div>
                                        <div class="text-xs text-gray-600">{{ $order->defectReport->defect_type_display }}</div>
                                    @else
                                        <span class="text-gray-500">Keine Meldung</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        @if($order->status === 'requested') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'approved') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'ordered') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                                        @elseif($order->status === 'received') bg-green-100 text-green-800
                                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $order->status_display }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm text-gray-900">{{ $order->requestedBy->name }}</div>
                                    @if($order->defectReport && $order->defectReport->reportingDepartment)
                                        <div class="text-xs text-gray-600">{{ $order->defectReport->reportingDepartment->name }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-900">
                                    {{ $order->requested_at->format('d.m.Y H:i') }}
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
                                        
                                        @if($order->status === 'ordered' && auth()->user()->role === 'procurement')
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
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">Keine Bestellungen gefunden.</p>
            </div>
        @endif
    </div>
        </div>
    </div>
</div>
