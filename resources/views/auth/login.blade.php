<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Acceso administrador | FERPLEC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white font-sans text-slate-800 antialiased" x-data="{ showPass: false, email: @js(old('email', '')), remember: false, loading: false }">

<div class="grid min-h-screen lg:grid-cols-[1.05fr_1fr]">
    {{-- Panel de marca --}}
    <aside class="relative hidden overflow-y-auto bg-[#0f243a] lg:sticky lg:top-0 lg:flex lg:h-screen lg:flex-col lg:justify-between">
        <img src="{{ asset('images/hero-ferplec.jpg') }}" alt="" aria-hidden="true"
             class="pointer-events-none absolute inset-0 h-full w-full object-cover object-center opacity-30 saturate-50">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0b1f33] via-[#0f243a]/92 to-[#0b1f33]/95"></div>
        <div class="absolute inset-0 opacity-[0.07]" style="background-image:linear-gradient(#fff 1px,transparent 1px),linear-gradient(90deg,#fff 1px,transparent 1px);background-size:44px 44px"></div>
        <div class="absolute -left-24 top-1/4 h-96 w-96 rounded-full bg-[#E66A19]/25 blur-[110px]"></div>
        <div class="absolute -right-20 bottom-0 h-80 w-80 rounded-full bg-[#22A366]/20 blur-[110px]"></div>
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-[#0f243a] via-[#0f243a]/60 to-transparent"></div>

        <div class="relative z-10 p-10 xl:p-14">
            <a href="{{ route('home') }}" class="inline-flex flex-col leading-[0.9]">
                <span class="text-[42px] font-black tracking-tight xl:text-[52px]">
                    <span class="text-white">FER</span><span class="text-[#E66A19]">PLEC</span>
                </span>
                <span class="mt-2 text-[9px] uppercase tracking-[0.28em] text-white/45">Ferretería • Plomería • Electricidad</span>
            </a>
        </div>

        <div class="relative z-10 max-w-lg p-10 xl:p-14">
            <h1 class="text-3xl font-black leading-[1.1] tracking-tight text-white xl:text-[2.6rem]">
                Gestiona tu catálogo<br>
                <span class="text-[#E66A19]">desde un solo lugar.</span>
            </h1>
            <p class="mt-4 max-w-md text-[15px] leading-relaxed text-white/55">
                Productos, categorías, tips y comentarios de tu tienda FERPLEC, con control total y en tiempo real.
            </p>

            <ul class="mt-9 space-y-3.5">
                @foreach ([
                    ['📦', 'Catálogo de productos', 'Alta, edición y stock de cada artículo'],
                    ['💡', 'Tips y guías', 'Contenido optimizado y programado'],
                    ['💬', 'Comentarios', 'Modera y aprueba lo que dice tu comunidad'],
                ] as [$icon, $title, $desc])
                    <li class="flex items-start gap-3.5 rounded-2xl border border-white/10 bg-white/[0.04] p-3.5 backdrop-blur-sm">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/10 text-base">{{ $icon }}</span>
                        <span class="leading-tight">
                            <span class="block text-sm font-bold text-white">{{ $title }}</span>
                            <span class="mt-0.5 block text-xs text-white/50">{{ $desc }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="relative z-10 flex items-center justify-between border-t border-white/10 px-10 py-6 text-[11px] text-white/35 xl:px-14">
            <span>© {{ date('Y') }} FERPLEC</span>
            <span class="flex items-center gap-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-[#22A366]"></span> Sistema operativo
            </span>
        </div>
    </aside>

    {{-- Formulario --}}
    <main class="flex items-center justify-center px-5 py-10 sm:px-10">
        <div class="w-full max-w-[420px]">

            <a href="{{ route('home') }}" class="mb-10 inline-flex flex-col leading-[0.9] lg:hidden">
                <span class="text-[34px] font-black tracking-tight">
                    <span class="text-[#173451]">FER</span><span class="text-[#E66A19]">PLEC</span>
                </span>
                <span class="mt-1.5 text-[8px] uppercase tracking-[0.24em] text-slate-400">Ferretería • Plomería • Electricidad</span>
            </a>

            <div class="animate-fade-up">
                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Acceso restringido
                </span>

                <h2 class="mt-5 text-[2rem] font-black leading-tight tracking-tight text-[#173451] sm:text-[2.4rem]">
                    Bienvenido de nuevo
                </h2>
                <p class="mt-2 text-[15px] text-slate-500">
                    Ingresa tus credenciales para acceder al panel de administración.
                </p>
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="mt-9 space-y-5" @submit="loading = true">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-[13px] font-semibold text-slate-700">Correo electrónico</label>
                    <div class="group relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#E66A19]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        </span>
                        <input id="email" type="email" name="email" x-model="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="h-[52px] w-full rounded-xl border border-slate-200 bg-white pl-12 pr-4 text-[15px] text-slate-800 shadow-sm transition-all placeholder:text-slate-300 hover:border-slate-300 focus:border-[#E66A19] focus:outline-none focus:ring-4 focus:ring-[#E66A19]/12 @error('email') border-red-300 focus:border-red-400 focus:ring-red-500/10 @enderror"
                               placeholder="tucorreo@ferplec.com">
                    </div>
                    @error('email')
                        <p class="mt-2 flex items-center gap-1.5 text-[13px] font-medium text-red-600">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-[13px] font-semibold text-slate-700">Contraseña</label>
                    <div class="group relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#E66A19]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        </span>
                        <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password"
                               class="h-[52px] w-full rounded-xl border border-slate-200 bg-white pl-12 pr-14 text-[15px] text-slate-800 shadow-sm transition-all placeholder:text-slate-300 hover:border-slate-300 focus:border-[#E66A19] focus:outline-none focus:ring-4 focus:ring-[#E66A19]/12 @error('password') border-red-300 focus:border-red-400 focus:ring-red-500/10 @enderror"
                               placeholder="••••••••">
                        <button type="button" @click="showPass = !showPass"
                                class="absolute right-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600"
                                :aria-label="showPass ? 'Ocultar contraseña' : 'Mostrar contraseña'">
                            <svg x-show="!showPass" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg x-show="showPass" x-cloak class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 flex items-center gap-1.5 text-[13px] font-medium text-red-600">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-0.5">
                    <label class="inline-flex cursor-pointer select-none items-center gap-2.5 text-[13px] text-slate-600">
                        <input type="checkbox" name="remember" value="1" x-model="remember" class="sr-only">
                        <span class="grid h-[18px] w-[18px] place-items-center rounded-[6px] border transition-all"
                              :class="remember ? 'border-[#E66A19] bg-[#E66A19] shadow-sm shadow-[#E66A19]/30' : 'border-slate-300 bg-white'">
                            <svg x-show="remember" x-cloak class="h-3 w-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </span>
                        Recordarme
                    </label>
                    <span class="text-[13px] text-slate-400">Acceso sólo para personal</span>
                </div>

                <button type="submit" class="group relative flex h-[52px] w-full items-center justify-center gap-2.5 overflow-hidden rounded-xl bg-[#173451] text-[15px] font-bold text-white shadow-lg shadow-[#173451]/20 transition-all hover:bg-[#0f243a] hover:shadow-xl hover:shadow-[#173451]/25 active:scale-[0.99] disabled:opacity-70">
                    <span x-show="!loading">Ingresar al panel</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2.5">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                        Ingresando…
                    </span>
                    <span x-show="!loading" class="pointer-events-none absolute inset-y-0 right-0 w-1/3 -skew-x-12 bg-white/15 opacity-0 transition-opacity group-hover:opacity-100 group-hover:animate-[shine_0.9s_ease-out]"></span>
                </button>
            </form>

            <div class="mt-8 flex items-center gap-3">
                <span class="h-px flex-1 bg-slate-200"></span>
                <span class="text-[11px] font-medium uppercase tracking-[0.18em] text-slate-400">Acceso rápido</span>
                <span class="h-px flex-1 bg-slate-200"></span>
            </div>

            <button type="button" @click="email = 'admin@ferplec.com'; document.getElementById('password')?.focus()"
                    class="mt-4 flex w-full items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-slate-50/70 px-4 py-3 text-left transition-all hover:border-[#E66A19]/60 hover:bg-[#E66A19]/5">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-white text-[#173451] shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </span>
                <span class="min-w-0 leading-tight">
                    <span class="block text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-400">Cuenta de demostración</span>
                    <span class="block truncate text-[14px] font-semibold text-[#173451]">admin@ferplec.com</span>
                </span>
                <svg class="ml-auto h-4 w-4 shrink-0 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </button>

            <p class="mt-8 text-center text-[13px] text-slate-400">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 font-medium transition-colors hover:text-[#173451]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    Volver al sitio
                </a>
            </p>
        </div>
    </main>
</div>

<style>
    @keyframes shine {
        0% { transform: translateX(-120%) skewX(-12deg); }
        100% { transform: translateX(320%) skewX(-12deg); }
    }
</style>
</body>
</html>
