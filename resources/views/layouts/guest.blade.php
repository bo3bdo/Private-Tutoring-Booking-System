<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        @php
            $isRegister = request()->routeIs('register');
            $bgImage = $isRegister 
                ? 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2071&q=80'
                : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2071&q=80';
            $gradientColor = $isRegister ? 'via-emerald-900' : 'via-blue-900';
        @endphp
        
        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-900/95 {{ $gradientColor }}/90 to-slate-800/95 z-10"></div>
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $bgImage }}');"></div>
            </div>
            
            <div class="max-w-md w-full relative z-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
