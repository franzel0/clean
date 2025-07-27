@extends('components.layouts.app')

@section('title', 'Berichte')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Berichte & Statistiken</h1>
        <p class="text-gray-600 mb-8">Diese Seite wurde zur neuen Livewire-Komponente verschoben.</p>
        <a href="{{ route('reports.index') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium">
            Zu den Berichten
        </a>
    </div>
</div>
@endsection
