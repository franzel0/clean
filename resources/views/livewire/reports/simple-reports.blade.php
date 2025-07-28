<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Berichte & Statistiken</h1>
            <p class="mt-1 text-sm text-gray-600">
                Übersicht über das Instrumenten-Management-System
            </p>
        </div>

        @if(isset($error))
            <div class="bg-red-50 border-2 border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Fehler beim Laden der Berichte: {{ $error }}
                </div>
            </div>
        @endif

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Instruments -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6 cursor-help" 
                 title="Gesamtanzahl aller registrierten Instrumente im System. {{ $functionalInstruments }} davon sind funktionsfähig und einsatzbereit.">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tools text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Instrumente</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalInstruments) }}</p>
                        <p class="text-sm text-gray-500">{{ $functionalInstruments }} funktionsfähig</p>
                    </div>
                </div>
            </div>

            <!-- Available -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6 cursor-help" 
                 title="Instrumente mit Status 'Verfügbar' - diese sind sofort einsatzbereit und können für Operationen verwendet werden.">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Verfügbar</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($availableInstruments) }}</p>
                        <p class="text-sm text-gray-500">Einsatzbereit</p>
                    </div>
                </div>
            </div>

            <!-- Defective -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6 cursor-help" 
                 title="Instrumente mit Status 'Defekt' - diese sind beschädigt und dürfen nicht verwendet werden bis sie repariert sind.">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Defekt</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($defectiveInstruments) }}</p>
                        <p class="text-sm text-gray-500">Reparatur nötig</p>
                    </div>
                </div>
            </div>

            <!-- Containers -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6 cursor-help" 
                 title="Gesamtanzahl aller Container im System. {{ $activeContainers }} Container sind aktiv und werden verwendet.">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Container</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalContainers) }}</p>
                        <p class="text-sm text-gray-500">{{ $activeContainers }} aktiv</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Status Distribution -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Instrument Status Verteilung</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $statusLabels = [
                                'available' => ['label' => 'Verfügbar', 'color' => 'green'],
                                'in_use' => ['label' => 'Im Einsatz', 'color' => 'blue'],
                                'defective' => ['label' => 'Defekt', 'color' => 'red'],
                                'in_repair' => ['label' => 'In Reparatur', 'color' => 'yellow'],
                                'out_of_service' => ['label' => 'Außer Betrieb', 'color' => 'gray']
                            ];
                            $total = array_sum($statusStats);
                        @endphp
                        
                        @foreach($statusLabels as $status => $config)
                            @php
                                $count = $statusStats[$status] ?? 0;
                                $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded mr-3 bg-{{ $config['color'] }}-500"></div>
                                    <span class="text-sm font-medium text-gray-700">{{ $config['label'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 mr-3">{{ $count }}</span>
                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-{{ $config['color'] }}-500" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 ml-2 w-10">{{ number_format($percentage, 1) }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Container Status -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Container Status</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Complete Containers -->
                        <div class="text-center p-4 bg-green-50 rounded-lg border-2 border-green-200 cursor-help" 
                             title="Container, in denen alle Instrumente funktionsfähig und einsatzbereit sind.">
                            <div class="flex items-center justify-center mb-2">
                                <i class="fas fa-check-circle text-green-600 text-2xl mr-2"></i>
                                <span class="text-2xl font-bold text-green-800">{{ $completeContainers }}</span>
                            </div>
                            <p class="text-sm font-medium text-green-700">Vollständige Container</p>
                            <p class="text-xs text-green-600">Alle Instrumente funktionsfähig</p>
                        </div>
                        
                        <!-- Incomplete Containers -->
                        <div class="text-center p-4 bg-orange-50 rounded-lg border-2 border-orange-200 cursor-help" 
                             title="Container mit mindestens einem defekten oder nicht verfügbaren Instrument - nur eingeschränkt verwendbar.">
                            <div class="flex items-center justify-center mb-2">
                                <i class="fas fa-exclamation-triangle text-orange-600 text-2xl mr-2"></i>
                                <span class="text-2xl font-bold text-orange-800">{{ $incompleteContainers }}</span>
                            </div>
                            <p class="text-sm font-medium text-orange-700">Unvollständige Container</p>
                            <p class="text-xs text-orange-600">Mit Einschränkungen verwendbar</p>
                        </div>

                        <!-- Out of Service Containers -->
                        @if($outOfServiceContainers > 0)
                        <div class="text-center p-4 bg-red-50 rounded-lg border-2 border-red-200 cursor-help" 
                             title="Container, die außer Betrieb sind und nicht für Operationen verwendet werden können.">
                            <div class="flex items-center justify-center mb-2">
                                <i class="fas fa-times-circle text-red-600 text-2xl mr-2"></i>
                                <span class="text-2xl font-bold text-red-800">{{ $outOfServiceContainers }}</span>
                            </div>
                            <p class="text-sm font-medium text-red-700">Außer Betrieb</p>
                            <p class="text-xs text-red-600">Nicht verwendbar</p>
                        </div>
                        @endif

                        <!-- Percentage -->
                        @php
                            $completePercentage = $activeContainers > 0 ? ($completeContainers / $activeContainers) * 100 : 0;
                        @endphp
                        <div class="pt-4 border-t-2 border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Vollständigkeitsrate</span>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($completePercentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="h-3 bg-green-500 rounded-full" style="width: {{ $completePercentage }}%"></div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('containers.index') }}" 
                               class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                Alle Container anzeigen →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Defect Reports -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
                <div class="px-6 py-4 border-b-2 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Defektmeldungen</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Statistics -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center cursor-help" 
                                 title="Gesamtanzahl aller Defektmeldungen, die jemals im System erstellt wurden.">
                                <p class="text-2xl font-bold text-gray-900">{{ $totalDefectReports }}</p>
                                <p class="text-sm text-gray-600">Gesamt</p>
                            </div>
                            <div class="text-center cursor-help" 
                                 title="Defektmeldungen die noch nicht abgeschlossen sind - diese Instrumente benötigen Reparatur oder weitere Bearbeitung.">
                                <p class="text-2xl font-bold text-red-600">{{ $openDefectReports }}</p>
                                <p class="text-sm text-gray-600">Offen</p>
                            </div>
                            <div class="text-center cursor-help" 
                                 title="Defektmeldungen, die in den letzten 30 Tagen erstellt wurden - zeigt aktuelle Probleme auf.">
                                <p class="text-2xl font-bold text-blue-600">{{ $recentDefectReports }}</p>
                                <p class="text-sm text-gray-600">30 Tage</p>
                            </div>
                        </div>
                        
                        <!-- Recent Defects -->
                        @if($recentDefects->count() > 0)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Aktuelle Defekte</h4>
                                <div class="space-y-2">
                                    @foreach($recentDefects->take(3) as $defect)
                                        <div class="flex items-center text-sm">
                                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                            <span class="flex-1 text-gray-900">{{ $defect->instrument->name }}</span>
                                            <span class="text-gray-500">{{ $defect->reported_at->diffForHumans() }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('defect-reports.index') }}" 
                                       class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Alle Defektmeldungen anzeigen →
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Navigation -->
        <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200">
            <div class="px-6 py-4 border-b-2 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Schnellzugriff</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <a href="{{ route('instruments.index') }}" 
                       class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all">
                        <i class="fas fa-tools text-blue-600 text-lg mr-3"></i>
                        <span class="font-medium text-gray-900">Instrumente verwalten</span>
                    </a>
                    
                    <a href="{{ route('containers.index') }}" 
                       class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-all">
                        <i class="fas fa-box text-purple-600 text-lg mr-3"></i>
                        <span class="font-medium text-gray-900">Container verwalten</span>
                    </a>
                    
                    <a href="{{ route('defect-reports.create') }}" 
                       class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-red-300 hover:bg-red-50 transition-all">
                        <i class="fas fa-plus text-red-600 text-lg mr-3"></i>
                        <span class="font-medium text-gray-900">Defekt melden</span>
                    </a>
                    
                    <a href="{{ route('movements.index') }}" 
                       class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-all">
                        <i class="fas fa-route text-green-600 text-lg mr-3"></i>
                        <span class="font-medium text-gray-900">Bewegungshistorie</span>
                    </a>
                    
                    <a href="{{ route('purchase-orders.index') }}" 
                       class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-yellow-300 hover:bg-yellow-50 transition-all">
                        <i class="fas fa-shopping-cart text-yellow-600 text-lg mr-3"></i>
                        <span class="font-medium text-gray-900">Bestellungen verwalten</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
