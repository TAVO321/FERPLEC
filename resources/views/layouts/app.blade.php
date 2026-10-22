<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FERPLEC — Ferretería, Plomería y Electricidad fácil')</title>
    <meta name="description" content="@yield('description', 'Todo para tus proyectos. Materiales que hacen posibles tus ideas. FERPLEC - Ferretería, Plomería y Electricidad en Bolivia.')">
    <meta property="og:title" content="@yield('title', 'FERPLEC')">
    <meta property="og:description" content="@yield('description', 'Todo para tus proyectos. Materiales que hacen posibles tus ideas.')">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#173451">
    <link rel="icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-white text-gray-900 antialiased pb-20 lg:pb-0" x-data>
    @include('partials.header')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
        {{-- cart float removed by design request --}}
    @stack('scripts')
</body>
</html>
