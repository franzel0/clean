<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.movement_history') }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $movements->total() }} {{ __('messages.movements_found') }}</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.search') }}</label>
                <input type="text" 
                       wire:model.live="search" 
                       placeholder="{{ __('messages.instrument') }} {{ __('messages.notes') }}..." 
                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.movement_type') }}</label>
                <select wire:model.live="typeFilter" 
                        class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('messages.all_types') }}</option>
                    @foreach($movementTypes as $type)
                        <option value="{{ $type }}">
                            @switch($type)
                                @case('dispatch') {{ __('messages.dispatch') }} @break
                                @case('return') {{ __('messages.return') }} @break
                                @case('transfer') {{ __('messages.transfer') }} @break
                                @case('sterilization') {{ __('messages.sterilization') }} @break
                                @case('repair') {{ __('messages.repair') }} @break
                                @default {{ ucfirst($type) }}
                            @endswitch
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.instrument') }}</label>
                <select wire:model.live="instrumentFilter" 
                        class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('messages.all_instruments') }}</option>
                    @foreach($instruments as $instrument)
                        <option value="{{ $instrument->id }}">{{ $instrument->name }} ({{ $instrument->serial_number }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.from_date') }}</label>
                <input type="date" 
                       wire:model.live="startDate" 
                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.to_date') }}</label>
                <input type="date" 
                       wire:model.live="endDate" 
                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Movements List -->
    @if($movements->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 overflow-hidden">
            @foreach($movements as $movement)
                <div class="border-b-2 border-gray-200 last:border-b-0">
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <span class="h-12 w-12 rounded-full 
                                    @if($movement->movement_type === 'dispatch') bg-blue-500 
                                    @elseif($movement->movement_type === 'return') bg-green-500
                                    @elseif($movement->movement_type === 'transfer') bg-purple-500
                                    @elseif($movement->movement_type === 'sterilization') bg-cyan-500
                                    @elseif($movement->movement_type === 'repair') bg-orange-500
                                    @else bg-gray-500 
                                    @endif 
                                    flex items-center justify-center border-2
                                    @if($movement->movement_type === 'dispatch') border-blue-700
                                    @elseif($movement->movement_type === 'return') border-green-700
                                    @elseif($movement->movement_type === 'transfer') border-purple-700
                                    @elseif($movement->movement_type === 'sterilization') border-cyan-700
                                    @elseif($movement->movement_type === 'repair') border-orange-700
                                    @else border-gray-700
                                    @endif">
                                    @if($movement->movement_type === 'dispatch')
                                        <i class="fas fa-arrow-right text-white"></i>
                                    @elseif($movement->movement_type === 'return')
                                        <i class="fas fa-arrow-left text-white"></i>
                                    @elseif($movement->movement_type === 'transfer')
                                        <i class="fas fa-exchange-alt text-white"></i>
                                    @elseif($movement->movement_type === 'sterilization')
                                        <i class="fas fa-shield-alt text-white"></i>
                                    @elseif($movement->movement_type === 'repair')
                                        <i class="fas fa-wrench text-white"></i>
                                    @else
                                        <i class="fas fa-circle text-white"></i>
                                    @endif
                                </span>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">
                                            {{ $movement->movement_type_display }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-2">
                                            <a href="{{ route('instruments.show', $movement->instrument) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-medium">
                                                {{ $movement->instrument->name }}
                                            </a>
                                            ({{ $movement->instrument->serial_number }})
                                        </p>
                                        
                                        <!-- Movement Details -->
                                        <div class="space-y-1 text-sm text-gray-700">
                                            @php
                                                $statusBefore = $movement->status_before_display;
                                                $statusAfter = $movement->status_after_display;
                                                $hasStatusChange = $statusBefore && $statusAfter && $statusBefore !== $statusAfter;
                                                $hasStatus = $statusBefore || $statusAfter;
                                            @endphp

                                            @if($hasStatusChange)
                                                <p>
                                                    <i class="fas fa-exchange-alt text-gray-400 mr-2"></i>
                                                    Status: 
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-300">
                                                        {{ $statusBefore }}
                                                    </span>
                                                    <i class="fas fa-arrow-right mx-2 text-gray-400"></i>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-300">
                                                        {{ $statusAfter }}
                                                    </span>
                                                </p>
                                            @elseif($hasStatus)
                                                <p>
                                                    <i class="fas fa-info-circle text-gray-400 mr-2"></i>
                                                    Status: 
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300">
                                                        {{ $statusAfter ?: $statusBefore }}
                                                    </span>
                                                </p>
                                            @endif
                                            
                                            @if($movement->fromDepartment || $movement->toDepartment)
                                                <p>
                                                    <i class="fas fa-building text-gray-400 mr-2"></i>
                                                    @if($movement->fromDepartment && $movement->toDepartment)
                                                        {{ $movement->fromDepartment->name }} → {{ $movement->toDepartment->name }}
                                                    @elseif($movement->fromDepartment)
                                                        Von: {{ $movement->fromDepartment->name }}
                                                    @elseif($movement->toDepartment)
                                                        Nach: {{ $movement->toDepartment->name }}
                                                    @endif
                                                </p>
                                            @endif

                                            @if($movement->fromContainer || $movement->toContainer)
                                                <p>
                                                    <i class="fas fa-box text-gray-400 mr-2"></i>
                                                    @if($movement->fromContainer && $movement->toContainer)
                                                        {{ $movement->fromContainer->name }} → {{ $movement->toContainer->name }}
                                                    @elseif($movement->fromContainer)
                                                        Von Container: {{ $movement->fromContainer->name }}
                                                    @elseif($movement->toContainer)
                                                        In Container: {{ $movement->toContainer->name }}
                                                    @endif
                                                </p>
                                            @endif

                                            @if($movement->notes)
                                                <p class="bg-gray-50 p-2 rounded border border-gray-200">
                                                    <i class="fas fa-sticky-note text-gray-400 mr-2"></i>
                                                    {{ $movement->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Timestamp & User -->
                                    <div class="flex-shrink-0 text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $movement->performed_at ? $movement->performed_at->format('d.m.Y') : 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ $movement->performed_at ? $movement->performed_at->format('H:i') : 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $movement->performedBy->name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="px-6 py-4 border-t-2 border-gray-200 bg-gray-50">
                {{ $movements->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Keine Bewegungen gefunden</h3>
                <p class="text-gray-600 mb-6">Es wurden keine Bewegungen gefunden, die Ihren Suchkriterien entsprechen.</p>
            </div>
        </div>
    @endif
</div>
