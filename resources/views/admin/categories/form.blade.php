@php($category = $category ?? null)

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-semibold text-slate-700">Nombre *</label>
        <input name="name" value="{{ old('name', $category?->name) }}" required
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
        @error('name') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700">Icono (emoji)</label>
        <input name="icon" value="{{ old('icon', $category?->icon) }}" maxlength="10" placeholder="🚿"
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
        @error('icon') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-4 grid gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-semibold text-slate-700">Color *</label>
        <input name="color" type="color" value="{{ old('color', $category?->color ?? '#f59e0b') }}"
               class="mt-1.5 h-11 w-full cursor-pointer rounded-xl border border-slate-200 p-1 shadow-sm">
        @error('color') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700">Orden</label>
        <input name="sort_order" type="number" min="0" value="{{ old('sort_order', $category?->sort_order ?? 0) }}"
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
        @error('sort_order') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-4">
    <label class="block text-sm font-semibold text-slate-700">Descripción</label>
    <textarea name="description" rows="2" class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">{{ old('description', $category?->description) }}</textarea>
    @error('description') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
</div>

<div class="mt-5">
    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category?->is_active ?? true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
        Visible en el sitio
    </label>
</div>
