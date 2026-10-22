<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — FERPLEC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F4F6F8] font-sans text-slate-800 antialiased" x-data="{ sidebar: false }">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 flex w-[272px] -translate-x-full flex-col bg-[#0f243a] transition-transform duration-300 ease-out lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
           :class="sidebar ? 'translate-x-0 shadow-2xl' : ''">

        <div class="flex h-[72px] shrink-0 items-center gap-3 border-b border-white/10 px-6">
            <a href="{{ route('home') }}" class="group flex flex-col leading-[0.9]">
                <span class="text-[26px] font-black tracking-tight transition-transform group-hover:scale-[1.03]">
                    <span class="text-white">FER</span><span class="text-[#E66A19]">PLEC</span>
                </span>
                <span class="mt-1 text-[7px] uppercase tracking-[0.2em] text-white/35">Ferretería • Plomería • Electricidad</span>
            </a>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto p-4">
            <p class="px-3 pb-2 pt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-white/25">Gestión</p>

            @php
                $navItems = [
                    ['admin.dashboard', '📊', 'Dashboard', 'admin.dashboard'],
                    ['admin.products.index', '📦', 'Productos', 'admin.products.*'],
                    ['admin.categories.index', '🏷️', 'Categorías', 'admin.categories.*'],
                    ['admin.tips.index', '💡', 'Tips', 'admin.tips.*'],
                    ['admin.tip-comments.index', '💬', 'Comentarios', 'admin.tip-comments.*'],
                    ['admin.settings.index', '⚙️', 'Configuración', 'admin.settings.*'],
                ];
            @endphp

            @foreach ($navItems as [$route, $icon, $label, $match])
                @php $active = request()->routeIs($match); @endphp
                <a href="{{ route($route) }}"
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all {{ $active ? 'bg-[#E66A19] text-white shadow-lg shadow-[#E66A19]/25' : 'text-white/60 hover:bg-white/[0.07] hover:text-white' }}">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg text-[15px] transition-transform group-hover:scale-110 {{ $active ? 'bg-white/20' : 'bg-white/[0.06]' }}">{{ $icon }}</span>
                    <span>{{ $label }}</span>
                    @if ($active)
                        <span class="ml-auto h-1.5 w-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
            @endforeach

            <div class="!my-4 border-t border-white/10"></div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="group flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-white/60 transition-all hover:bg-red-500/15 hover:text-red-300">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-white/[0.06] text-[15px] transition-transform group-hover:scale-110">🚪</span>
                    Cerrar sesión
                </button>
            </form>
        </nav>

        <div class="shrink-0 border-t border-white/10 p-4">
            <div class="flex items-center gap-3 rounded-xl bg-white/[0.05] p-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-gradient-to-br from-[#E66A19] to-[#c2550f] text-sm font-black text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <div class="min-w-0 leading-tight">
                    <span class="block truncate text-[13px] font-bold text-white">{{ auth()->user()->name }}</span>
                    <span class="block truncate text-[11px] text-white/40">{{ auth()->user()->email }}</span>
                </div>
            </div>
        </div>
    </aside>

    <div x-show="sidebar" x-cloak x-transition.opacity.duration.200ms @click="sidebar = false"
         class="fixed inset-0 z-30 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

    {{-- Contenido --}}
    <div class="flex min-w-0 flex-1 flex-col">

        <header class="sticky top-0 z-20 flex h-[72px] items-center gap-4 border-b border-slate-200/80 bg-white/85 px-4 backdrop-blur-md lg:px-8">
            <button @click="sidebar = !sidebar" class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:border-[#E66A19] hover:text-[#E66A19] lg:hidden" aria-label="Menú">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <div class="min-w-0 flex-1">
                <h1 class="truncate text-[17px] font-black tracking-tight text-[#173451]">@yield('admin-title', 'Dashboard')</h1>
                <p class="hidden truncate text-xs text-slate-400 sm:block">@yield('admin-subtitle', 'Panel de administración de FERPLEC')</p>
            </div>

            <a href="{{ route('home') }}" target="_blank" class="hidden shrink-0 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-[13px] font-semibold text-slate-600 transition-colors hover:border-[#173451] hover:text-[#173451] sm:flex">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8M12 3a13.5 13.5 0 000 18M12 3a13.5 13.5 0 010 18"/></svg>
                Ver sitio
            </a>

            <div class="flex shrink-0 items-center gap-2.5 rounded-xl border border-slate-200 bg-white py-1.5 pl-1.5 pr-3.5">
                <span class="grid h-8 w-8 place-items-center rounded-lg bg-gradient-to-br from-[#E66A19] to-[#c2550f] text-[13px] font-black text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <span class="hidden text-[13px] font-semibold text-slate-700 sm:block">{{ auth()->user()->name }}</span>
            </div>
        </header>

        <main class="flex-1 p-4 lg:p-8">
            @if (session('success') || session('status'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-medium text-emerald-800">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') ?? session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm font-medium text-red-800">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('admin-content')
        </main>
    </div>
</div>
</body>
</html>
