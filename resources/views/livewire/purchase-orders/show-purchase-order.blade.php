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
                    <!-- Status Actions -->
                    @if($order->status !== 'cancelled' && $order->status !== 'completed')
                        <div x-data="{ open: false }" class="relative" wire:key="status-dropdown-{{ $order->status }}">
                            <button @click="open = !open" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Status ändern!!
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-10">
                                <div class="py-1">
                                    @foreach($availableStatuses as $statusTransition)
                                        <button wire:click="openStatusModal('{{ $statusTransition['id'] }}')" 
                                                class="block w-full text-left px-4 py-2 text-sm @if($statusTransition['name'] === 'Storniert') text-red-700 hover:bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif">
                                            {{ $statusTransition['name'] }}
                                        </button>
                                        @if($statusTransition['name'] === 'Storniert' && !$loop->last)
                                            <div class="border-t border-gray-100"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
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
            
            @if(session()->has('message'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif
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
                            Erstellt am {{ $order->requested_at ? $order->requested_at->format('d.m.Y H:i') : $order->created_at->format('d.m.Y H:i') }}
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
                    <!-- Current Details Display -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Bestellnummer</h3>
                            <p class="text-gray-900">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Angefordert von</h3>
                            <p class="text-gray-900">{{ $order->requestedBy->name }}</p>
                        </div>
                        @if($order->manufacturer_id)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Aktueller Hersteller</h3>
                                <p class="text-gray-900">{{ $order->manufacturer_display }}</p>
                            </div>
                        @endif
                        @if($order->estimated_cost)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Geschätzte Kosten</h3>
                                <p class="text-gray-900">{{ number_format($order->estimated_cost, 2) }} €</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Edit Form -->
                    <form wire:submit.prevent="updateDetails">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="manufacturer_id" class="text-sm font-medium text-gray-700 mb-2 block">Hersteller ändern</label>
                                <x-alpine-autocomplete 
                                    :options="$manufacturers->toArray()"
                                    wire-model="manufacturer_id"
                                    :value="$manufacturer_id"
                                    placeholder="Hersteller auswählen..."
                                    display-field="name"
                                    value-field="id"
                                    :search-fields="['name', 'contact_person']"
                                    secondary-display-field="contact_person"
                                    :error="$errors->first('manufacturer_id')"
                                />
                            </div>
                            <div>
                                <label for="actualCost" class="text-sm font-medium text-gray-700 mb-2 block">Tatsächliche Kosten</label>
                                <input type="number" 
                                       id="actualCost" 
                                       wire:model="actualCost" 
                                       step="0.01" 
                                       min="0" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0.00">
                                @error('actualCost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="expectedDelivery" class="text-sm font-medium text-gray-700 mb-2 block">Erwartete Lieferung</label>
                                <input type="date" 
                                       id="expectedDelivery" 
                                       wire:model="expectedDelivery" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                @error('expectedDelivery') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label for="notes" class="text-sm font-medium text-gray-700 mb-2 block">Notizen</label>
                            <textarea id="notes" 
                                      wire:model="notes" 
                                      rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Zusätzliche Informationen zur Bestellung..."></textarea>
                            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Details speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Related Defect Report -->
            @if($order->defectReport)
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
            @endif

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
                                                <p>{{ $order->requested_at ? $order->requested_at->format('d.m.Y H:i') : 'Nicht verfügbar' }}</p>
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
                                                <p>{{ $order->approved_at ? $order->approved_at->format('d.m.Y H:i') : 'Nicht verfügbar' }}</p>
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
                                                <p>{{ $order->ordered_at ? $order->ordered_at->format('d.m.Y H:i') : 'Nicht verfügbar' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($order->status === 'shipped' || $order->received_at)
                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                    @endif
                                </div>
                            </li>
                            @endif

                            <!-- Shipped -->
                            @if($order->status === 'shipped' || $order->received_at)
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1V8a1 1 0 00-1-1h-3z"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-500">Bestellung versandt</p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>
                                                    @if($order->received_at)
                                                        Versandt (vor Lieferung)
                                                    @else
                                                        Status gesetzt
                                                    @endif
                                                </p>
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
                                                <p>{{ $order->received_at ? $order->received_at->format('d.m.Y H:i') : 'Nicht verfügbar' }}</p>
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
    
    <!-- Status Update Modal -->
    @if($showStatusModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" wire:ignore.self>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <!-- Background overlay -->
            <div class="fixed inset-0" style="background-color: rgba(75, 85, 99, 0.5);" wire:click="closeModal"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl max-w-lg w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Status ändern
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Sind Sie sicher, dass Sie den Status zu 
                        <strong>{{ $this->getStatusDisplayName($newStatus) }}</strong>
                        ändern möchten?
                    </p>
                    
                    @if($this->getStatusDisplayName($newStatus) === 'Storniert')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                            <p class="text-sm text-red-700">
                                ⚠️ Achtung: Diese Bestellung wird storniert und kann nicht rückgängig gemacht werden.
                            </p>
                        </div>
                    @endif
                </div>
                
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button wire:click="closeModal" 
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg text-sm font-medium">
                        Abbrechen
                    </button>
                    <button wire:click="confirmStatusUpdate" 
                            class="px-4 py-2 @if($this->getStatusDisplayName($newStatus) === 'Storniert') bg-red-600 hover:bg-red-700 @else bg-blue-600 hover:bg-blue-700 @endif text-white rounded-lg text-sm font-medium">
                        @if($this->getStatusDisplayName($newStatus) === 'Storniert') Stornieren @else Bestätigen @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
