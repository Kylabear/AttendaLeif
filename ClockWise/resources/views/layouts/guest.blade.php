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
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans text-gray-900 antialiased app-page-bg">
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute left-0 top-1/4 h-[360px] w-[360px] rounded-full bg-violet-400/30 blur-[95px]"></div>
            <div class="absolute bottom-0 right-0 h-[320px] w-[320px] rounded-full bg-fuchsia-400/25 blur-[85px]"></div>
        </div>
        <div class="relative flex min-h-screen flex-col items-center justify-center pt-6 sm:pt-0">
            @yield('content')
        </div>
    </body>
</html>
