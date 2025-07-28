@props([
 'title',
 'description',
])

<div class="flex w-full flex-col text-center">
 <!-- Logo -->
 <div class="flex justify-center mb-6">
     <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-20 w-auto object-contain">
 </div>
 
 <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ config('app.name') }}</h1>
 <h2 class="text-xl text-gray-700 mb-2">{{ $title }}</h2>
 <p class="text-gray-600">{{ $description }}</p>
</div>
