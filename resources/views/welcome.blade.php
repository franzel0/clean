<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
        <div class="flex flex-col items-center justify-center min-h-screen p-6">
            <div class="w-full max-w-md mx-auto">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mb-4">
                        <div class=" mx-auto flex items-center justify-center mb-4">
                            <img src="{{ asset('img/logo.png') }}" class="h-32 w-32 border-grey-400 rounded-lg shadow-xl" alt="" srcset="">
                        </div>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">
                        {{ config('app.name') }}
                    </h1>
                    <h1 class="text-xl font-bold text-slate-900 mb-2">
                        Demo - nur zu Testzwecken
                    </h1>
                    <p class="text-slate-600">
                        Krankenhaus Sterilisations- und Defektmeldesystem
                    </p>
                </div>

                <!-- Login Card -->
                <div class="bg-white shadow-xl rounded-2xl p-8 border border-slate-200">
                    <h2 class="text-xl font-semibold text-slate-800 text-center mb-6">
                        Anmeldung
                    </h2>

                    <div class="space-y-4 mb-6">
                        <p class="text-sm text-slate-600">
                            Verwenden Sie einen der folgenden Test-Accounts:
                        </p>

                        <div class="space-y-3">
                            <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                                <div class="text-sm">
                                    <div class="font-medium text-blue-800">Administrator</div>
                                    <div class="text-blue-700 mt-1">
                                        Email: admin@hospital.de<br>
                                        Passwort: password
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-green-50 rounded-lg border-l-4 border-green-400">
                                <div class="text-sm">
                                    <div class="font-medium text-green-800">Sterilisationspersonal</div>
                                    <div class="text-green-700 mt-1">
                                        Email: steril@hospital.de<br>
                                        Passwort: password
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-amber-50 rounded-lg border-l-4 border-amber-400">
                                <div class="text-sm">
                                    <div class="font-medium text-amber-800">OP-Personal</div>
                                    <div class="text-amber-700 mt-1">
                                        Email: op@hospital.de<br>
                                        Passwort: password
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-purple-50 rounded-lg border-l-4 border-purple-400">
                                <div class="text-sm">
                                    <div class="font-medium text-purple-800">Einkauf</div>
                                    <div class="text-purple-700 mt-1">
                                        Email: purchase@hospital.de<br>
                                        Passwort: password
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('login') }}"
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Zur Anmeldung
                        </a>

                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="w-full flex justify-center items-center py-3 px-4 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                                </svg>
                                Zum Dashboard
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-8">
                    <p class="text-sm text-slate-500">
                        Entwickelt für effizientes Krankenhausmanagement
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>