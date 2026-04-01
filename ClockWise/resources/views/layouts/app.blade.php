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

        <!-- Styles / Scripts (Laravel Mix — run `npm run build` in ClockWise after CSS changes) -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased app-page-bg text-gray-900">
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -left-20 top-0 h-[420px] w-[420px] rounded-full bg-violet-400/35 blur-[100px]"></div>
            <div class="absolute -right-10 bottom-20 h-[380px] w-[380px] rounded-full bg-fuchsia-400/30 blur-[90px]"></div>
            <div class="absolute left-1/2 top-1/3 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-indigo-300/25 blur-[110px]"></div>
        </div>
        <div class="relative min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="border-b border-white/40 bg-white/45 shadow-sm shadow-black/5 backdrop-blur-xl">
                    <div class="mx-auto flex max-w-7xl items-center justify-center px-4 py-6 text-center sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
