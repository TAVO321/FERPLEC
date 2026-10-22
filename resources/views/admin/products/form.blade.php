@php($product = $product ?? null)

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-semibold text-slate-700">Código *</label>
        <input name="code" value="{{ old('code', $product?->code) }}" required
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
        @error('code') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700">Categoría *</label>
        <select name="category_id" required class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none">
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $product?->category_id) == $cat->id)>{{ $cat->icon }} {{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-4">
    <label class="block text-sm font-semibold text-slate-700">Nombre *</label>
    <input name="name" value="{{ old('name', $product?->name) }}" required
           class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
    @error('name') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
</div>

<div class="mt-4">
    <label class="block text-sm font-semibold text-slate-700">Imagen del producto</label>
    <div class="mt-2 flex items-center gap-4">
        <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 text-2xl text-slate-300">
            @if ($product?->main_image)
                <img src="{{ asset('storage/'.ltrim($product->main_image, '/')) }}" alt="" class="h-full w-full object-cover">
            @else
                🖼️
            @endif
        </div>
        <div class="flex-1">
            <input type="file" name="image" accept="image/*"
                   class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-600 file:px-4 file:py-2 file:text-sm file:font-bold file:text-white hover:file:bg-brand-700">
            <p class="mt-1.5 text-xs text-slate-400">JPG, PNG o WEBP. Máx 2 MB. Deja vacío para conservar la actual.</p>
            @if ($product?->main_image)
                <label class="mt-2 inline-flex items-center gap-2 text-xs text-red-500">
                    <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300">
                    Quitar imagen actual
                </label>
            @endif
        </div>
    </div>
    @error('image') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
</div>

<div class="mt-4">
    <label class="block text-sm font-semibold text-slate-700">Descripción</label>
    <textarea name="description" rows="2" class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">{{ old('description', $product?->description) }}</textarea>
    @error('description') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
</div>

<div class="mt-4 grid gap-4 sm:grid-cols-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700">Marca</label>
        <input name="brand" value="{{ old('brand', $product?->brand) }}"
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
        @error('brand') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700">Precio (Bs) <span class="font-normal text-slate-400">— vacío = consultar</span></label>
        <input name="price" type="number" step="0.01" min="0" value="{{ old('price', $product?->price) }}"
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
        @error('price') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700">Unidad de medida</label>
        <select name="unit" class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none">
            @php($units = ['unidad','metro','rollo','paquete','caja','litro','kg','barra','tubo','pieza','ml','m','cm','juego','par'])
            @foreach($units as $u)
                <option value="{{ $u }}" @selected(old('unit', $product?->unit ?? 'unidad') == $u)>{{ $u }}</option>
            @endforeach
        </select>
        @error('unit') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700">Stock mínimo</label>
        <input name="min_stock" type="number" min="0" value="{{ old('min_stock', $product?->min_stock ?? 0) }}"
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
        @error('min_stock') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700">Stock *</label>
        <input name="stock" type="number" min="0" value="{{ old('stock', $product?->stock ?? 0) }}" required
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
        @error('stock') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-4 grid gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-semibold text-slate-700">Apartado</label>
        <input name="stock_apartado" type="number" min="0" value="{{ old('stock_apartado', $product?->stock_apartado ?? 0) }}"
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
        @error('stock_apartado') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700">Vendido</label>
        <input name="stock_vendido" type="number" min="0" value="{{ old('stock_vendido', $product?->stock_vendido ?? 0) }}"
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
        @error('stock_vendido') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-5 flex flex-wrap gap-6">
    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true)) class="rounded border-slate-300 text-brand-600 focus:border-brand-500">
        Visible en el sitio
    </label>
    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product?->is_featured)) class="rounded border-slate-300 text-brand-600 focus:border-brand-500">
        Destacado en portada
    </label>
</div>
