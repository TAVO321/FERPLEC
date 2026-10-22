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
    <meta property="og:site_name" content="FERPLEC">
    <meta property="og:locale" content="es_BO">
    <meta name="theme-color" content="#173451">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-white text-gray-900 antialiased" x-data>
    @include('partials.header')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
    @stack('scripts')
</body>
</html>
