@extends('layouts.admin')

@section('admin-title', 'Guías')
@section('admin-subtitle', 'Tips y guías publicados en el sitio')

@section('admin-content')

<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h1 class="text-xl font-black text-[#173451]">
        Guías y Tips
        <span class="text-sm font-medium text-slate-400">({{ $guides->total() }})</span>
    </h1>
    <a href="{{ route('admin.tips.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-700">
        + Nueva guía
    </a>
</div>

{{-- Tarjetas apiladas en móvil --}}
<div class="mt-4 space-y-3 lg:hidden">
    @forelse ($guides as $guide)
        <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="font-semibold leading-snug text-[#173451]">{{ $guide->title }}</p>
                    <p class="mt-0.5 truncate font-mono text-[11px] text-slate-400">/guia/{{ $guide->slug }}</p>
                </div>
                @if ($guide->is_published)
                    <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">Publicada</span>
                @else
                    <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-500">Borrador</span>
                @endif
            </div>

            <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-400">
                <span class="rounded-full bg-brand-50 px-2.5 py-1 font-semibold text-brand-700">{{ $guide->area_label }}</span>
                <span>Actualizado {{ $guide->updated_at->format('d/m/Y H:i') }}</span>
            </div>

            <div class="mt-3 flex items-center gap-2 border-t border-slate-100 pt-3">
                <a href="{{ route('admin.tips.edit', ['tip' => $guide->getRouteKey()]) }}"
                   class="flex-1 rounded-xl bg-brand-50 px-3 py-2.5 text-center text-sm font-bold text-brand-700 transition hover:bg-brand-100">
                    Editar
                </a>
                <form method="POST" action="{{ route('admin.tips.destroy', ['tip' => $guide->getRouteKey()]) }}"
                      onsubmit="return confirm('¿Eliminar esta guía?')">
                    @csrf @method('DELETE')
                    <button class="w-full rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-sm font-bold text-red-600 transition hover:bg-red-100">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-12 text-center text-sm text-slate-400">
            Sin guías todavía.
        </div>
    @endforelse

    @if ($guides->hasPages())
        <div class="pt-2">{{ $guides->links() }}</div>
    @endif
</div>

{{-- Tabla en desktop --}}
<div class="mt-4 hidden overflow-x-auto rounded-2xl border border-slate-200 bg-white lg:block">
    <table class="min-w-full divide-y divide-slate-100 text-sm">
        <thead class="bg-slate-50">
            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                <th class="px-4 py-3">Título</th>
                <th class="px-4 py-3">Área</th>
                <th class="px-4 py-3 text-center">Estado</th>
                <th class="px-4 py-3">Actualizado</th>
                <th class="px-4 py-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($guides as $guide)
                <tr class="hover:bg-slate-50/60">
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-900">{{ $guide->title }}</p>
                        <p class="text-xs text-slate-400">/guia/{{ $guide->slug }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="rounded-full bg-brand-50 px-2 py-0.5 text-[11px] font-semibold text-brand-700">{{ $guide->area_label }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if ($guide->is_published)
                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-700">Publicada</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-500">Borrador</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-400">{{ $guide->updated_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.tips.edit', ['tip' => $guide->getRouteKey()]) }}" class="font-semibold text-brand-700 hover:text-brand-800">Editar</a>
                        <form method="POST" action="{{ route('admin.tips.destroy', ['tip' => $guide->getRouteKey()]) }}" class="inline"
                              onsubmit="return confirm('¿Eliminar esta guía?')">
                            @csrf @method('DELETE')
                            <button class="ml-3 font-semibold text-red-500 hover:text-red-600">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">Sin guías todavía.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($guides->hasPages())
    <div class="mt-4 hidden lg:block">{{ $guides->links() }}</div>
@endif
@endsection
