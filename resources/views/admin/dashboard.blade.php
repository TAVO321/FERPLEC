@extends('layouts.admin')

@section('admin-title', 'Dashboard')
@section('admin-subtitle', 'Resumen general de tu tienda FERPLEC')

@section('admin-content')

{{-- Tarjetas de métricas --}}
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

    <a href="{{ route('admin.products.index') }}"
       class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 transition-all hover:-translate-y-0.5 hover:border-[#173451]/20 hover:shadow-xl hover:shadow-slate-200/60">
        <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-[#173451]/[0.04] transition-transform group-hover:scale-150"></div>
        <div class="relative flex items-start justify-between">
            <span class="grid h-11 w-11 place-items-center rounded-xl bg-[#173451]/[0.07] text-xl">📦</span>
            <svg class="h-4 w-4 text-slate-300 transition-all group-hover:translate-x-0.5 group-hover:text-[#E66A19]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </div>
        <p class="relative mt-4 text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Productos</p>
        <p class="relative mt-1 text-[2rem] font-black leading-none tracking-tight text-[#173451]">{{ $products }}</p>
        <p class="relative mt-2 text-xs text-slate-500"><span class="font-bold text-emerald-600">{{ $activeProducts }}</span> activos en catálogo</p>
    </a>

    <a href="{{ route('admin.categories.index') }}"
       class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 transition-all hover:-translate-y-0.5 hover:border-[#E66A19]/25 hover:shadow-xl hover:shadow-slate-200/60">
        <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-[#E66A19]/[0.06] transition-transform group-hover:scale-150"></div>
        <div class="relative flex items-start justify-between">
            <span class="grid h-11 w-11 place-items-center rounded-xl bg-[#E66A19]/10 text-xl">🏷️</span>
            <svg class="h-4 w-4 text-slate-300 transition-all group-hover:translate-x-0.5 group-hover:text-[#E66A19]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </div>
        <p class="relative mt-4 text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Categorías</p>
        <p class="relative mt-1 text-[2rem] font-black leading-none tracking-tight text-[#173451]">{{ $categories }}</p>
        <p class="relative mt-2 text-xs text-slate-500">Estructura del catálogo</p>
    </a>

    <a href="{{ route('admin.tips.index') }}"
       class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 transition-all hover:-translate-y-0.5 hover:border-[#22A366]/25 hover:shadow-xl hover:shadow-slate-200/60">
        <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-[#22A366]/[0.07] transition-transform group-hover:scale-150"></div>
        <div class="relative flex items-start justify-between">
            <span class="grid h-11 w-11 place-items-center rounded-xl bg-[#22A366]/10 text-xl">💡</span>
            <svg class="h-4 w-4 text-slate-300 transition-all group-hover:translate-x-0.5 group-hover:text-[#E66A19]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </div>
        <p class="relative mt-4 text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Tips</p>
        <p class="relative mt-1 text-[2rem] font-black leading-none tracking-tight text-[#173451]">{{ $guides }}</p>
        <p class="relative mt-2 text-xs text-slate-500">Guías publicadas</p>
    </a>

    <a href="{{ route('admin.products.index') }}"
       class="group relative overflow-hidden rounded-2xl border border-amber-200/80 bg-gradient-to-br from-amber-50 to-white p-5 transition-all hover:-translate-y-0.5 hover:shadow-xl hover:shadow-amber-200/50">
        <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-amber-400/10 transition-transform group-hover:scale-150"></div>
        <div class="relative flex items-start justify-between">
            <span class="grid h-11 w-11 place-items-center rounded-xl bg-amber-500/15 text-xl">⚠️</span>
            <svg class="h-4 w-4 text-amber-300 transition-all group-hover:translate-x-0.5 group-hover:text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </div>
        <p class="relative mt-4 text-[11px] font-bold uppercase tracking-[0.14em] text-amber-600">Stock bajo</p>
        <p class="relative mt-1 text-[2rem] font-black leading-none tracking-tight text-amber-700">{{ $lowStock->count() }}</p>
        <p class="relative mt-2 text-xs text-amber-600/80">≤ 5 unidades disponibles</p>
    </a>
</div>

{{-- Accesos rápidos --}}
<div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <a href="{{ route('admin.products.create') }}" class="group flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 transition-all hover:border-[#E66A19]/30 hover:shadow-lg hover:shadow-slate-200/50">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-[#E66A19]/10 text-lg">➕</span>
        <span class="leading-tight">
            <span class="block text-sm font-bold text-[#173451]">Nuevo producto</span>
            <span class="block text-xs text-slate-400">Agregar al catálogo</span>
        </span>
    </a>
    <a href="{{ route('admin.tips.create') }}" class="group flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 transition-all hover:border-[#E66A19]/30 hover:shadow-lg hover:shadow-slate-200/50">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-[#E66A19]/10 text-lg">📝</span>
        <span class="leading-tight">
            <span class="block text-sm font-bold text-[#173451]">Nuevo tip</span>
            <span class="block text-xs text-slate-400">Publicar una guía</span>
        </span>
    </a>
    <a href="{{ route('admin.tip-comments.index') }}" class="group flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 transition-all hover:border-[#E66A19]/30 hover:shadow-lg hover:shadow-slate-200/50">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-[#E66A19]/10 text-lg">💬</span>
        <span class="leading-tight">
            <span class="block text-sm font-bold text-[#173451]">Moderar comentarios</span>
            <span class="block text-xs text-slate-400">Aprobar o ocultar</span>
        </span>
    </a>
    <a href="{{ route('admin.settings.index') }}" class="group flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 transition-all hover:border-[#E66A19]/30 hover:shadow-lg hover:shadow-slate-200/50">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-[#E66A19]/10 text-lg">⚙️</span>
        <span class="leading-tight">
            <span class="block text-sm font-bold text-[#173451]">Configuración</span>
            <span class="block text-xs text-slate-400">WhatsApp y marca</span>
        </span>
    </a>
</div>

{{-- Listados --}}
<div class="mt-6 grid gap-6 lg:grid-cols-2">

    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 class="text-sm font-bold text-[#173451]">Productos con poco stock</h2>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-[#E66A19] transition-colors hover:text-[#c2550f]">Ver todos →</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($lowStock as $product)
                <li class="flex items-center justify-between gap-3 px-5 py-3 text-sm transition-colors hover:bg-slate-50/70">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-slate-900">{{ $product->name }}</p>
                        <p class="font-mono text-xs text-slate-400">{{ $product->code }}</p>
                    </div>
                    <span class="ml-3 shrink-0 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">
                        {{ $product->stock_disponible ?? $product->stock ?? 0 }} uds
                    </span>
                </li>
            @empty
                <li class="px-5 py-10 text-center text-sm text-slate-400">Sin productos con stock bajo. 🎉</li>
            @endforelse
        </ul>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 class="text-sm font-bold text-[#173451]">Últimos productos agregados</h2>
            <a href="{{ route('admin.products.create') }}" class="rounded-lg bg-[#173451] px-3 py-1.5 text-xs font-bold text-white transition-colors hover:bg-[#0f243a]">+ Nuevo</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($recentProducts as $product)
                <li class="flex items-center justify-between gap-3 px-5 py-3 text-sm transition-colors hover:bg-slate-50/70">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-slate-900">{{ $product->name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ $product->category->name ?? 'Sin categoría' }} · {{ $product->created_at->diffForHumans(short: true) }}</p>
                    </div>
                    <a href="{{ route('admin.products.edit', $product) }}" class="ml-3 shrink-0 text-xs font-bold text-[#E66A19] transition-colors hover:text-[#c2550f]">Editar</a>
                </li>
            @empty
                <li class="px-5 py-10 text-center text-sm text-slate-400">No hay productos aún.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
