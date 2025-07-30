<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg border-r border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="text-center">
                    <div class="flex justify-center">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-full h-32 object-contain">
                    </div>
                    <h1 class="text-lg font-bold text-gray-900">
                        {{ config('app.name') }}
                    </h1>
                </div>
            </div>

            <nav class="mt-6">
                <div class="px-6 py-3 text-sm font-medium text-gray-700">
                    {{ __('messages.navigation') }}
                </div>
                <div class="mt-2">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50 border-r-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        {{ __('messages.dashboard') }}
                    </a>

                    <a href="{{ route('instruments.index') }}"
                        class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('instruments.*') ? 'text-blue-600 bg-blue-50 border-r-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        {{ __('messages.instruments') }}
                    </a>

                    <a href="{{ route('defect-reports.index') }}"
                        class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('defect-reports.*') ? 'text-blue-600 bg-blue-50 border-r-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        {{ __('messages.defect_reports') }}
                    </a>

                    <a href="{{ route('purchase-orders.index') }}"
                        class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('purchase-orders.*') ? 'text-blue-600 bg-blue-50 border-r-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        {{ __('messages.purchase_orders') }}
                    </a>

                    <a href="{{ route('containers.index') }}"
                        class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('containers.*') ? 'text-blue-600 bg-blue-50 border-r-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        {{ __('messages.containers') }}
                    </a>

                    <a href="{{ route('movements.index') }}"
                        class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('movements.*') ? 'text-blue-600 bg-blue-50 border-r-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        {{ __('messages.movements') }}
                    </a>

                    <a href="{{ route('reports.index') }}"
                        class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('reports.*') ? 'text-blue-600 bg-blue-50 border-r-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        {{ __('messages.reports') }}
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('users.index') }}"
                            class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('users.*') ? 'text-blue-600 bg-blue-50 border-r-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Benutzerverwaltung
                        </a>

                        <a href="{{ route('app-settings.index') }}"
                            class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('app-settings.*') ? 'text-blue-600 bg-blue-50 border-r-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            App Einstellungen
                        </a>
                    @endif
                </div>
            </nav>

            <!-- User Menu -->
            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-200">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full flex items-center text-left hover:bg-gray-50 rounded-lg p-2 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-white">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-700">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="open = false"
                         class="absolute bottom-full left-0 w-full mb-2 bg-white rounded-lg shadow-lg border border-gray-200 py-1"
                         style="display: none;">
                        
                        <a href="{{ route('settings.profile') }}" 
                           wire:navigate
                           class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profil-Einstellungen
                        </a>
                        
                        <div class="border-t border-gray-100 my-1"></div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 text-left">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Abmelden
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header with Language Switcher -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div></div> <!-- Empty space for balance -->
                    <div class="flex items-center space-x-4">
                        <livewire:language-switcher />
                    </div>
                </div>
            </header>
            
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>