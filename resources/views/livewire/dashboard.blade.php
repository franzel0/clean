<div class="container mx-auto px-4 py-8">
 <div class="mb-8">
 <h1 class="text-3xl font-bold text-gray-900">Instrumenten Management Dashboard</h1>
 </div>
 
 <!-- Quick Actions -->
 <div class="mb-8">
     <h2 class="text-lg font-semibold text-gray-900 mb-4">Schnellaktionen</h2>
     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
         <!-- Neues Instrument -->
         <a href="{{ route('instruments.create') }}" 
            class="group flex items-center p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 shadow-sm hover:shadow-md">
             <div class="flex-shrink-0">
                 <div class="w-10 h-10 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                     <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                     </svg>
                 </div>
             </div>
             <div class="ml-4">
                 <p class="text-sm font-medium text-gray-900 group-hover:text-blue-900">Neues Instrument</p>
                 <p class="text-xs text-gray-500 group-hover:text-blue-700">Instrument registrieren</p>
             </div>
         </a>
         
         <!-- Defekt melden -->
         <a href="{{ route('defect-reports.create') }}" 
            class="group flex items-center p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-red-300 hover:bg-red-50 transition-all duration-200 shadow-sm hover:shadow-md">
             <div class="flex-shrink-0">
                 <div class="w-10 h-10 bg-red-100 group-hover:bg-red-200 rounded-lg flex items-center justify-center transition-colors">
                     <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                     </svg>
                 </div>
             </div>
             <div class="ml-4">
                 <p class="text-sm font-medium text-gray-900 group-hover:text-red-900">Defekt melden</p>
                 <p class="text-xs text-gray-500 group-hover:text-red-700">Schnell Problem melden</p>
             </div>
         </a>
         
         <!-- Neue Bestellung -->
         <a href="{{ route('purchase-orders.create') }}" 
            class="group flex items-center p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-all duration-200 shadow-sm hover:shadow-md">
             <div class="flex-shrink-0">
                 <div class="w-10 h-10 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center transition-colors">
                     <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                     </svg>
                 </div>
             </div>
             <div class="ml-4">
                 <p class="text-sm font-medium text-gray-900 group-hover:text-purple-900">Neue Bestellung</p>
                 <p class="text-xs text-gray-500 group-hover:text-purple-700">Ersatz bestellen</p>
             </div>
         </a>
         
         <!-- Container erstellen -->
         <a href="{{ route('containers.create') }}" 
            class="group flex items-center p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-all duration-200 shadow-sm hover:shadow-md">
             <div class="flex-shrink-0">
                 <div class="w-10 h-10 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center transition-colors">
                     <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                     </svg>
                 </div>
             </div>
             <div class="ml-4">
                 <p class="text-sm font-medium text-gray-900 group-hover:text-green-900">Neuer Container</p>
                 <p class="text-xs text-gray-500 group-hover:text-green-700">Container anlegen</p>
             </div>
         </a>
     </div>
 </div>
 
    <!-- Statistiken -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stats-card p-6 cursor-help" 
             title="Gesamtanzahl aller registrierten Instrumente im System, unabhängig vom Status.">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $stats['total_instruments'] }}</div>
                <div class="text-sm text-gray-600">Instrumente gesamt</div>
            </div>
        </div>

        <div class="stats-card p-6 cursor-help" 
             title="Anzahl der Instrumente mit Status 'Defekt' - diese sind beschädigt und dürfen nicht verwendet werden.">
            <div class="text-center">
                <div class="text-3xl font-bold text-red-600">{{ $stats['defective_instruments'] }}</div>
                <div class="text-sm text-gray-600">Defekte Instrumente</div>
            </div>
        </div>

        <div class="stats-card p-6 cursor-help" 
             title="Defektmeldungen, die noch nicht abgeschlossen sind und weitere Bearbeitung oder Reparatur benötigen.">
            <div class="text-center">
                <div class="text-3xl font-bold text-yellow-600">{{ $stats['open_reports'] }}</div>
                <div class="text-sm text-gray-600">Offene Meldungen</div>
            </div>
        </div>

        <div class="stats-card p-6 cursor-help" 
             title="Bestellungen für neue Instrumente oder Ersatzteile, die noch nicht abgeschlossen oder geliefert wurden.">
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600">{{ $stats['pending_orders'] }}</div>
                <div class="text-sm text-gray-600">Offene Bestellungen</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Aktuelle Meldungen -->
        <div class="dashboard-card p-6">
 <div class="flex justify-between items-center mb-4">
 <h2 class="text-xl font-semibold text-gray-900">Aktuelle Defektmeldungen</h2>
 <a href="{{ route('defect-reports.create') }}" 
 class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
 Neue Meldung
 </a>
 </div>

 @if($recent_reports->count() > 0)
 <div class="space-y-3">
 @foreach($recent_reports as $report)
 <div class="border-l-4 
 @if($report->severity === 'critical') border-red-500
 @elseif($report->severity === 'high') border-orange-500
 @elseif($report->severity === 'medium') border-yellow-500
 @else border-green-500
 @endif
 pl-4 py-2">
 <div class="font-medium text-gray-900">{{ $report->instrument->name }}</div>
 <div class="text-sm text-gray-600">
 {{ $report->report_number }} - {{ $report->defect_type_display }}
 </div>
 <div class="text-xs text-gray-500">
 von {{ $report->reportedBy->name }} am {{ $report->reported_at->format('d.m.Y H:i') }}
 </div>
 </div>
 @endforeach
 </div>
 
 <a href="{{ route('defect-reports.index') }}" 
 class="inline-block mt-4 text-blue-600 hover:text-blue-800 text-sm">
 Alle Meldungen anzeigen →
 </a>
 @else
 <p class="text-gray-500">Keine aktuellen Meldungen vorhanden.</p>
 @endif
 </div>        <!-- Instrumente nach Status -->
        <div class="dashboard-card p-6">
 <h2 class="text-xl font-semibold text-gray-900 mb-4">Instrumente nach Status</h2>
 
 @if($instruments_by_status->count() > 0)
 <div class="space-y-3">
 @foreach($instruments_by_status as $status => $count)
 <div class="flex justify-between items-center">
 <span class="text-gray-700">
 @switch($status)
 @case('available') Verfügbar @break
 @case('in_use') Im Einsatz @break
 @case('defective') Defekt @break
 @case('in_repair') In Reparatur @break
 @case('out_of_service') Außer Betrieb @break
 @default {{ $status }}
 @endswitch
 </span>
 <span class="font-bold text-gray-900">{{ $count }}</span>
 </div>
 @endforeach
 </div>
 
 <a href="{{ route('instruments.index') }}" 
 class="inline-block mt-4 text-blue-600 hover:text-blue-800 text-sm">
 Alle Instrumente anzeigen →
 </a>
 @else
 <p class="text-gray-500">Keine Instrumente vorhanden.</p>
 @endif
 </div>
 </div>    <!-- Meldungen nach Abteilung -->
    @if($reports_by_department->count() > 0)
        <div class="dashboard-card p-6 mt-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Meldungen nach Abteilung</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($reports_by_department as $department => $count)
                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg border border-gray-200 hover:shadow-md transition-all duration-200 cursor-help" 
                         title="Anzahl der Defektmeldungen aus der Abteilung {{ $department }}. Hilft bei der Identifizierung von Abteilungen mit häufigen Problemen.">
                        <div class="text-2xl font-bold text-blue-600">{{ $count }}</div>
                        <div class="text-sm text-gray-600">{{ $department }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
