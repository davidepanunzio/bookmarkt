<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|lora:500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="{{ route('home') }}">
                    <x-application-logo class="text-2xl" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-sm overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

            {{-- Footer minimale: sulle pagine di accesso non serve quello completo del negozio --}}
            <div class="mt-8 mb-6 flex gap-4 text-xs text-gray-400">
                <span>&copy; {{ date('Y') }} BookMarkt</span>
                <a href="{{ route('legal.privacy') }}" class="hover:text-gray-600">Informativa sulla privacy</a>
                <a href="{{ route('legal.termini') }}" class="hover:text-gray-600">Termini e condizioni</a>
            </div>
        </div>
    </body>
</html>
