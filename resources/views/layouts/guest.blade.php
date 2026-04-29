<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#0a0a0a] font-sans text-white antialiased">
        {{-- Ambient background blurs --}}
        <div class="pointer-events-none fixed inset-0 z-0">
            <div class="absolute -left-40 -top-40 h-[500px] w-[500px] rounded-full bg-[#E50914]/8 blur-[120px]"></div>
            <div class="absolute right-0 top-1/3 h-[400px] w-[400px] rounded-full bg-[#E50914]/5 blur-[100px]"></div>
        </div>

        <div class="relative z-10">
            {{ $slot }}
        </div>
    </body>
</html>
