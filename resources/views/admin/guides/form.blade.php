@php($guide = $guide ?? null)

<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="block text-sm font-semibold text-slate-700">Título *</label>
        <input name="title" value="{{ old('title', $guide?->title) }}" required
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
        @error('title') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700">Categoría *</label>
        <select name="category" required class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none">
            @foreach ($areas as $key => $label)
                <option value="{{ $key }}" @selected(old('category', $guide?->category ?? $guide?->area) == $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('category') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700">Tiempo de lectura (min)</label>
        <input name="read_time" type="number" min="1" max="60" value="{{ old('read_time', $guide?->read_time ?? 3) }}"
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
        @error('read_time') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-semibold text-slate-700">Resumen (aparece en listados)</label>
        <input name="excerpt" value="{{ old('excerpt', $guide?->excerpt) }}" maxlength="500"
               class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
        @error('excerpt') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-semibold text-slate-700">Imagen / banner</label>
        <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="flex h-28 w-full max-w-xs shrink-0 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50">
                @if ($guide?->image)
                    <img src="{{ media_url($guide->image) }}" alt="" class="h-full w-full object-cover">
                @else
                    <span class="text-2xl text-slate-300">🖼️</span>
                @endif
            </div>
            <div class="flex-1">
                <input type="file" name="image_file" accept="image/*"
                       class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-600 file:px-4 file:py-2 file:text-sm file:font-bold file:text-white hover:file:bg-brand-700">
                <p class="mt-1.5 text-xs text-slate-500">JPG, PNG o WEBP. Máx 2 MB.</p>
                <div class="mt-2 flex flex-col gap-2">
                    <input name="image" value="{{ old('image', $guide?->image && ! Str::startsWith($guide?->image, ['tips/']) && ! Str::startsWith($guide?->image, ['/']) ? $guide?->image : null) }}" placeholder="https://... o ruta externa"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
                    <p class="text-xs text-slate-400">O pega una URL externa si prefieres no subir archivo.</p>
                    @if ($guide?->image)
                        <label class="inline-flex items-center gap-2 text-xs text-red-500">
                            <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300">
                            Quitar imagen actual
                        </label>
                    @endif
                </div>
            </div>
        </div>
        @error('image_file') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
        @error('image') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-semibold text-slate-700">Contenido (HTML permitido: h2, h3, p, ul, ol, li, strong) *</label>
        <textarea name="content" rows="14" required
                  class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono text-xs shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">{{ old('content', $guide?->content) }}</textarea>
        @error('content') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-5 flex flex-wrap gap-6">
    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $guide?->is_published ?? true)) class="rounded border-slate-300 text-brand-600 focus:border-brand-500">
        Publicado (visible en el sitio)
    </label>
    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $guide?->is_featured)) class="rounded border-slate-300 text-brand-600 focus:border-brand-500">
        Destacado
    </label>
</div>
