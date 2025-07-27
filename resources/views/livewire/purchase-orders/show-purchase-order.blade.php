<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Bestellung {{ $order->order_number }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Bestelldetails und Status
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button wire:click="downloadPdf" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                        PDF herunterladen
                    </button>
                    <a href="{{ route('purchase-orders.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Zurück zur Liste
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Status</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @switch($order->status)
                                @case('requested') bg-yellow-100 text-yellow-800 @break
                                @case('approved') bg-blue-100 text-blue-800 @break
                                @case('ordered') bg-purple-100 text-purple-800 @break
                                @case('shipped') bg-indigo-100 text-indigo-800 @break
                                @case('received') bg-green-100 text-green-800 @break
                                @case('completed') bg-green-100 text-green-800 @break
                                @case('cancelled') bg-red-100 text-red-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            {{ $order->status_display }}
                        </span>
                        <span class="ml-4 text-sm text-gray-600">
                            Erstellt am {{ $order->requested_at->format('d.m.Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Bestelldetails</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Bestellnummer</h3>
                            <p class="text-gray-900">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Angefordert von</h3>
                            <p class="text-gray-900">{{ $order->requestedBy->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Lieferant</h3>
                            <p class="text-gray-900">{{ $order->supplier }}</p>
                        </div>
                        @if($order->estimated_cost)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Geschätzte Kosten</h3>
                            <p class="text-gray-900">{{ number_format($order->estimated_cost, 2) }} €</p>
                        </div>
                        @endif
                        @if($order->actual_cost)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Tatsächliche Kosten</h3>
                            <p class="text-gray-900">{{ number_format($order->actual_cost, 2) }} €</p>
                        </div>
                        @endif
                        @if($order->expected_delivery)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Erwartete Lieferung</h3>
                            <p class="text-gray-900">{{ $order->expected_delivery->format('d.m.Y') }}</p>
                        </div>
                        @endif
                    </div>
                    
                    @if($order->notes)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Notizen</h3>
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Related Defect Report -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Zugehörige Defektmeldung</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Meldungsnummer</h3>
                            <p class="text-gray-900">
                                <a href="{{ route('defect-reports.show', $order->defectReport) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    {{ $order->defectReport->report_number }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Instrument</h3>
                            <p class="text-gray-900">{{ $order->defectReport->instrument->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Seriennummer</h3>
                            <p class="text-gray-900">{{ $order->defectReport->instrument->serial_number }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Gemeldet von</h3>
                            <p class="text-gray-900">{{ $order->defectReport->reportedBy->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Abteilung</h3>
                            <p class="text-gray-900">{{ $order->defectReport->reportingDepartment->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Defekttyp</h3>
                            <p class="text-gray-900">{{ $order->defectReport->defect_type_display }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Beschreibung</h3>
                        <p class="text-gray-900">{{ $order->defectReport->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Verlauf</h2>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <!-- Requested -->
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414-1.414L9 5.586 7.707 4.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L10 7.586z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Bestellung angefordert von 
                                                    <span class="font-medium text-gray-900">{{ $order->requestedBy->name }}</span>
                                                </p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>{{ $order->requested_at->format('d.m.Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($order->approved_at || $order->ordered_at || $order->received_at)
                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                    @endif
                                </div>
                            </li>

                            <!-- Approved -->
                            @if($order->approved_at)
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Bestellung genehmigt
                                                    @if($order->approvedBy)
                                                        von <span class="font-medium text-gray-900">{{ $order->approvedBy->name }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>{{ $order->approved_at->format('d.m.Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($order->ordered_at || $order->received_at)
                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                    @endif
                                </div>
                            </li>
                            @endif

                            <!-- Ordered -->
                            @if($order->ordered_at)
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-500">Bestellung aufgegeben</p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>{{ $order->ordered_at->format('d.m.Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($order->received_at)
                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                    @endif
                                </div>
                            </li>
                            @endif

                            <!-- Received -->
                            @if($order->received_at)
                            <li>
                                <div class="relative">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Bestellung erhalten
                                                    @if($order->receivedBy)
                                                        von <span class="font-medium text-gray-900">{{ $order->receivedBy->name }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>{{ $order->received_at->format('d.m.Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
