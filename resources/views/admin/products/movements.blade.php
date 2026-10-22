@extends('layouts.admin')

@section('admin-title', 'Movimientos de stock')

@section('admin-content')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-xl font-black text-slate-900">Movimientos de stock</h1>
        <p class="mt-1 text-sm text-slate-500">Historial de ajustes de precio y stock realizados desde el panel.</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">← Productos</a>
</div>

{{-- Tarjetas móviles --}}
<div class="mt-4 space-y-3 lg:hidden">
    @forelse ($movements as $movement)
        <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="font-semibold text-slate-900">{{ $movement->product?->name ?? '—' }}</p>
                    <p class="mt-0.5 font-mono text-xs text-slate-400">{{ $movement->product?->code }}</p>
                </div>
                <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">{{ $movement->field ?? $movement->type }}</span>
            </div>
            <div class="mt-2 flex items-center justify-between text-sm">
                <div>
                    <p class="text-xs text-slate-400">Cambio</p>
                    <p class="mt-0.5"><span class="text-slate-400">{{ $movement->previous_value }}</span> <span class="text-slate-300">→</span> <span class="font-bold text-slate-900">{{ $movement->new_value }}</span></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-400">Fecha</p>
                    <p class="mt-0.5 text-xs text-slate-500">{{ $movement->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <p class="mt-2 text-xs text-slate-400">Por: {{ $movement->user?->name ?? 'Sistema' }}</p>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-10 text-center text-sm text-slate-400">Sin movimientos registrados todavía.</div>
    @endforelse

    @if ($movements->hasPages())
        <div class="pt-2">{{ $movements->links() }}</div>
    @endif
</div>

{{-- Tabla desktop --}}
<div class="mt-4 hidden overflow-x-auto rounded-2xl border border-slate-200 bg-white lg:block">
    <table class="min-w-full divide-y divide-slate-100 text-sm">
        <thead class="bg-slate-50">
            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                <th class="px-4 py-3">Fecha</th>
                <th class="px-4 py-3">Producto</th>
                <th class="px-4 py-3">Campo</th>
                <th class="px-4 py-3 text-center">Antes → Después</th>
                <th class="px-4 py-3">Usuario</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($movements as $movement)
                <tr>
                    <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <span class="font-medium text-slate-900">{{ $movement->product?->name ?? '—' }}</span>
                        <span class="ml-1 font-mono text-xs text-slate-400">{{ $movement->product?->code }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600">{{ $movement->field ?? $movement->type }}</span>
                    </td>
                    <td class="px-4 py-3 text-center text-sm">
                        <span class="text-slate-400">{{ $movement->previous_value }}</span>
                        <span class="mx-1.5 text-slate-300">→</span>
                        <span class="font-bold text-slate-900">{{ $movement->new_value }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $movement->user?->name ?? 'Sistema' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">Sin movimientos registrados todavía.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($movements->hasPages())
    <div class="mt-4 hidden lg:block">{{ $movements->links() }}</div>
@endif
@endsection
