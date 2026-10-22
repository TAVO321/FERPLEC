@extends('layouts.admin')

@section('admin-title', 'Productos')

@section('admin-content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h1 class="text-xl font-black text-slate-900">Productos <span class="text-sm font-medium text-slate-400">({{ $products->total() }})</span></h1>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.products.movements') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Movimientos</a>
        <a href="{{ route('admin.products.export') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Exportar CSV</a>
        <button type="button" onclick="document.getElementById('import-box').classList.toggle('hidden')" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Importar CSV</button>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:text-brand-700">+ Nuevo producto</a>
    </div>
</div>

<div id="import-box" class="mt-4 hidden rounded-2xl border border-dashed border-brand-300 bg-brand-50/40 p-4">
    <form method="POST" action="{{ route('admin.products.import') }}" enctype="multipart/form-data" class="flex flex-col gap-3 sm:flex-row sm:items-center">
        @csrf
        <input type="file" name="file" accept=".csv,.txt" required class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-600 file:px-4 file:py-2 file:text-sm file:font-bold file:text-white hover:file:bg-brand-700">
        <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-slate-800">Subir CSV</button>
    </form>
    <p class="mt-2 text-xs text-slate-500">Columnas esperadas: <code class="font-mono">code, name, description, brand, categoria, price, stock_total, stock_apartado, stock_vendido, is_active, is_featured</code>. Los productos existentes (por código) se actualizan.</p>
</div>

<form method="GET" action="{{ route('admin.products.index') }}" class="mt-4 flex flex-col gap-2 sm:flex-row">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre, código o marca..."
           class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20 sm:max-w-xs">
    <select name="categoria" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none">
        <option value="">Todas las categorías</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->slug }}" @selected(request('categoria') === $cat->slug)>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Filtrar</button>
</form>

{{-- Tarjetas móviles --}}
<div class="mt-4 space-y-3 lg:hidden">
    @forelse ($products as $product)
        <div class="rounded-2xl border border-slate-200 bg-white p-4" x-data="{ edit: false }">
            <div class="flex items-start gap-3">
                <div class="shrink-0">
                    @if ($product->main_image)
                        <img src="{{ media_url($product->main_image) }}" alt="" class="h-12 w-12 rounded-lg object-cover">
                    @elseif ($product->category)
                        <span class="flex h-12 w-12 items-center justify-center rounded-lg text-lg" style="background: {{ $product->category->color }}15">{{ $product->category->icon }}</span>
                    @else
                        <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 text-slate-400">📦</span>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900">{{ $product->name }}</p>
                            <p class="mt-0.5 font-mono text-xs text-slate-400">{{ $product->code }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-1">
                            @if ($product->is_active)
                                <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-700">Activo</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-500">Oculto</span>
                            @endif
                            @if ($product->is_featured)
                                <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-bold text-amber-700">★</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                        @if ($product->category)
                            <span class="rounded-full px-2 py-0.5 font-semibold" style="background: {{ $product->category->color }}15; color: {{ $product->category->color }}">{{ $product->category->name }}</span>
                        @endif
                        @if ($product->brand)
                            <span class="text-slate-400">{{ $product->brand }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-xs text-slate-400">Precio</p>
                    <p class="mt-0.5 font-semibold text-slate-900">{{ $product->price !== null ? 'Bs. '.number_format($product->price, 2) : 'Consultar' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Stock</p>
                    <div x-show="!edit" class="mt-0.5 font-semibold {{ $product->stock_disponible > 5 ? 'text-emerald-600' : ($product->stock_disponible > 0 ? 'text-amber-600' : 'text-slate-400') }}">{{ $product->stock_disponible }}</div>
                    <form x-show="edit" x-cloak method="POST" action="{{ route('admin.products.stock', $product) }}" class="mt-1 flex items-center gap-1">
                        @csrf @method('PATCH')
                        <input type="hidden" name="field" value="stock">
                        <input type="number" min="0" name="value" value="{{ $product->stock }}" class="w-20 rounded-lg border border-slate-200 px-2 py-1 text-center text-xs">
                        <button class="rounded-lg bg-brand-600 px-2 py-1 text-[10px] font-bold text-white">✓</button>
                    </form>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3">
                <button type="button" @click="edit = !edit" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600" x-text="edit ? 'Cancelar' : 'Ajustar stock'"></button>
                <a href="{{ route('admin.products.edit', $product) }}" class="rounded-xl bg-brand-50 px-3 py-2 text-xs font-bold text-brand-700">Editar</a>
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('¿Eliminar el producto {{ $product->code }}?')">
                    @csrf @method('DELETE')
                    <button class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-600">Eliminar</button>
                </form>
            </div>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-10 text-center text-sm text-slate-400">Sin productos.</div>
    @endforelse

    <div class="pt-2">{{ $products->links() }}</div>
</div>

{{-- Tabla desktop --}}
<div class="mt-4 hidden overflow-x-auto rounded-2xl border border-slate-200 bg-white lg:block">
    <table class="min-w-full divide-y divide-slate-100 text-sm">
        <thead class="bg-slate-50">
            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                <th class="px-3 py-3"></th>
                <th class="px-3 py-3">Código</th>
                <th class="px-3 py-3">Nombre</th>
                <th class="px-3 py-3">Categoría</th>
                <th class="px-3 py-3">Marca</th>
                <th class="px-3 py-3 text-right">Precio</th>
                <th class="px-3 py-3 text-center">Stock</th>
                <th class="px-3 py-3 text-center">Estado</th>
                <th class="px-3 py-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($products as $product)
                <tr class="hover:bg-slate-50/60" x-data="{ edit: false }">
                    <td class="px-3 py-3">
                        @if ($product->main_image)
                            <img src="{{ media_url($product->main_image) }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                        @elseif ($product->category)
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg text-lg" style="background: {{ $product->category->color }}15">{{ $product->category->icon }}</span>
                        @else
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-400">📦</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 font-mono text-xs font-semibold text-slate-500">{{ $product->code }}</td>
                    <td class="px-3 py-3 font-medium text-slate-900">{{ $product->name }}</td>
                    <td class="px-3 py-3">
                        @if ($product->category)
                            <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold"
                                  style="background: {{ $product->category->color }}15; color: {{ $product->category->color }}">
                                {{ $product->category->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-slate-500">{{ $product->brand ?? '—' }}</td>
                    <td class="px-3 py-3 text-right">
                        <div x-show="!edit" class="font-semibold">{{ $product->price !== null ? 'Bs. '.number_format($product->price, 2) : '—' }}</div>
                        <form x-show="edit" x-cloak method="POST" action="{{ route('admin.products.stock', $product) }}" class="inline-flex items-center gap-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="field" value="price">
                            <input type="number" step="0.01" min="0" name="value" value="{{ $product->price }}" class="w-24 rounded-lg border border-slate-200 px-2 py-1 text-right text-xs">
                            <button class="rounded-lg bg-brand-600 px-2 py-1 text-[10px] font-bold text-white">✓</button>
                        </form>
                    </td>
                    <td class="px-3 py-3 text-center">
                        <div x-show="!edit" class="font-semibold {{ $product->stock_disponible > 5 ? 'text-emerald-600' : ($product->stock_disponible > 0 ? 'text-amber-600' : 'text-slate-400') }}">
                            {{ $product->stock_disponible }}
                        </div>
                        <form x-show="edit" x-cloak method="POST" action="{{ route('admin.products.stock', $product) }}" class="inline-flex items-center gap-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="field" value="stock">
                            <input type="number" min="0" name="value" value="{{ $product->stock }}" class="w-16 rounded-lg border border-slate-200 px-2 py-1 text-center text-xs">
                            <button class="rounded-lg bg-brand-600 px-2 py-1 text-[10px] font-bold text-white">✓</button>
                        </form>
                    </td>
                    <td class="px-3 py-3 text-center">
                        @if ($product->is_active)
                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-700">Activo</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-500">Oculto</span>
                        @endif
                        @if ($product->is_featured)
                            <span class="ml-1 rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-bold text-amber-700">★</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-right whitespace-nowrap">
                        <button type="button" @click="edit = !edit" class="font-semibold text-slate-500 hover:text-slate-700" x-text="edit ? 'Cancelar' : 'Stock'"></button>
                        <a href="{{ route('admin.products.edit', $product) }}" class="ml-3 font-semibold text-brand-700 hover:text-brand-800">Editar</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline"
                              onsubmit="return confirm('¿Eliminar el producto {{ $product->code }}?')">
                            @csrf @method('DELETE')
                            <button class="ml-3 font-semibold text-red-500 hover:text-red-600">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="px-4 py-10 text-center text-slate-400">Sin productos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $products->links() }}</div>
@endsection
