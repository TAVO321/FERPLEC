@extends('layouts.app')

@section('title', 'Catálogo de productos | FERPLEC')

@section('content')
<section class="relative overflow-hidden bg-[#173451] text-white">
    <picture>
        <source srcset="{{ asset('storage/banners/catalogo.webp') }}" type="image/webp">
        <img src="{{ asset('storage/banners/catalogo.jpg') }}" alt="Catálogo de productos FERPLEC" class="absolute inset-0 w-full h-full object-cover object-[65%_center]">
    </picture>
    <div class="absolute inset-0 bg-gradient-to-r from-[#173451] via-[#173451]/92 to-[#173451]/35"></div>
    <div class="relative max-w-7xl mx-auto px-4 py-10 sm:py-12 lg:py-14">
        <nav class="text-xs sm:text-sm text-white/60 mb-3">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Inicio</a>
            <span class="px-1">/</span>
            <span class="text-white/90">Productos</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight">Catálogo de productos</h1>
        <p class="text-white/80 mt-2 text-sm sm:text-base max-w-2xl">Ferretería, Plomería y Electricidad. Arma tu lista y envíala por WhatsApp para consultar precio y disponibilidad.</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 -mt-6 relative z-10">
    <div class="rounded-2xl bg-white shadow-xl ring-1 ring-gray-100 p-3 sm:p-4">
        <form action="{{ route('catalog') }}" method="GET" class="flex flex-col lg:flex-row gap-2 lg:gap-3 lg:items-center">
            <div class="flex-1 flex items-center gap-2">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre, código, marca..." class="w-full rounded-xl border border-gray-200 pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#E66A19]/20 focus:border-[#E66A19]">
                </div>
            </div>
            <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2">
                <select name="category" class="rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#E66A19]/20 focus:border-[#E66A19]">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                        @foreach($cat->children as $child)
                            <option value="{{ $child->id }}" @selected(request('category') == $child->id)>— {{ $child->name }}</option>
                        @endforeach
                    @endforeach
                </select>
                <select name="sort" class="rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#E66A19]/20 focus:border-[#E66A19]">
                    <option value="relevancia" @selected(request('sort')=='relevancia')>Relevancia</option>
                    <option value="nombre" @selected(request('sort')=='nombre')>Nombre</option>
                    <option value="precio_asc" @selected(request('sort')=='precio_asc')>Precio menor a mayor</option>
                    <option value="precio_desc" @selected(request('sort')=='precio_desc')>Precio mayor a menor</option>
                </select>
                <label class="col-span-2 sm:col-span-1 flex items-center justify-center gap-2 rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                    <input type="checkbox" name="disponible" value="1" @checked(request('disponible')=='1') class="rounded border-gray-300 text-[#E66A19] focus:ring-[#E66A19]/30">
                    <span>Solo disponibles</span>
                </label>
                <button class="col-span-2 sm:col-span-1 rounded-xl bg-[#E66A19] text-white px-6 py-2.5 text-sm font-semibold hover:bg-[#d15f14] active:scale-[0.98] transition-all">Aplicar filtros</button>
            </div>
        </form>
    </div>
<section class="max-w-7xl mx-auto px-4 mt-6">
    <div class="flex items-center justify-between gap-3">
        <p class="text-sm text-gray-600">
            Mostrando <span class="font-semibold text-[#173451]">{{ $products->firstItem() ?? 0 }}</span>–<span class="font-semibold text-[#173451]">{{ $products->lastItem() ?? 0 }}</span> de <span class="font-semibold text-[#173451]">{{ $products->total() }}</span> productos
        </p>
        @if(request()->hasAny(['q','category','brand','sort','disponible']))
            <a href="{{ route('catalog') }}" class="text-sm font-semibold text-[#E66A19] hover:text-[#d15f14]">Limpiar filtros</a>
        @endif
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-6">
    <div class="grid gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($products as $product)
        <article class="group rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden flex flex-col hover:shadow-lg hover:-translate-y-0.5 transition-all">
            <a href="{{ route('product.show', $product) }}" class="aspect-[4/3] bg-[#F4F6F8] block overflow-hidden">
                @if($product->main_image)
                    <img src="{{ asset('storage/'.$product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
            </a>
            <div class="p-4 flex flex-col gap-2 flex-1">
                <div class="text-[10px] sm:text-xs text-gray-400 flex items-center gap-2">
                    <span>{{ $product->code }}</span>
                    @if($product->brand)
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span>{{ $product->brand }}</span>
                    @endif
                </div>
                <a href="{{ route('product.show', $product) }}" class="font-semibold text-[#173451] hover:text-[#E66A19] line-clamp-2 leading-snug">{{ $product->name }}</a>
                <p class="hidden sm:block text-sm text-gray-500 line-clamp-2">{{ $product->short_description }}</p>
                <div class="mt-auto pt-1 flex items-end justify-between gap-2">
                    <div>
                        <div class="font-bold text-[#173451]">{{ $product->price_formatted }}</div>
                        @if($product->unit && $product->unit !== 'unidad')
                            <div class="text-[10px] sm:text-xs text-gray-500">Unidad: {{ $product->unit }}</div>
                        @endif
                    </div>
                    <span class="text-[10px] sm:text-xs {{ $product->in_stock ? 'text-[#22A366]' : 'text-gray-400' }}">{{ $product->in_stock ? 'Disponible' : 'Sin stock' }}</span>
                </div>
                <button @click="$store.cart.add({code: '{{ $product->code }}', name: '{{ addslashes($product->name) }}', price: {{ $product->price ?? 0 }}, unit: '{{ $product->unit }}', variant: null, stock: {{ $product->stock }}})" class="w-full rounded-lg bg-[#173451] text-white py-2 text-sm hover:bg-[#132a44] active:scale-[0.98] transition-all">Agregar a mi lista</button>
            </div>
        </article>
        @empty
        <div class="col-span-full text-center py-16">
            <div class="w-14 h-14 mx-auto rounded-full bg-[#F4F6F8] flex items-center justify-center text-gray-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <p class="mt-4 text-gray-500">No se encontraron productos con los filtros seleccionados.</p>
            <a href="{{ route('catalog') }}" class="inline-block mt-4 text-sm font-semibold text-[#E66A19]">Ver todo el catálogo</a>
        </div>
        @endforelse
    </div>    <div class="mt-8">
        {{ $products->links('partials.pagination') }}
    </div>
</section>
@endsection
