@extends('layouts.admin')

@section('admin-title', 'Categorías')

@section('admin-content')
<div class="flex items-center justify-between">
    <h1 class="text-xl font-black text-slate-900">Categorías</h1>
    <a href="{{ route('admin.categories.create') }}" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:text-brand-700">+ Nueva categoría</a>
</div>

<div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($categories as $category)
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl text-2xl"
                      style="background: {{ $category->color }}18">{{ $category->icon }}</span>
                @if ($category->is_active)
                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-700">Activa</span>
                @else
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-500">Inactiva</span>
                @endif
            </div>
            <h2 class="mt-3 font-bold text-slate-900">{{ $category->name }}</h2>
            <p class="mt-1 text-xs text-slate-400">{{ $category->products_count }} producto{{ $category->products_count === 1 ? '' : 's' }}</p>
            <div class="mt-4 flex gap-3 text-sm font-semibold">
                <a href="{{ route('admin.categories.edit', $category) }}" class="text-brand-700 hover:text-brand-800">Editar</a>
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                      onsubmit="return confirm('¿Eliminar la categoría {{ $category->name }}?')">
                    @csrf @method('DELETE')
                    <button class="text-red-500 hover:text-red-600">Eliminar</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-sm text-slate-400">Sin categorías.</p>
    @endforelse
</div>
@endsection
