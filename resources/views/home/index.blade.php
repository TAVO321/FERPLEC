@extends('layouts.app')

@section('title', 'FERPLEC — Ferretería, Plomería y Electricidad fácil | Todo para tus proyectos')

@section('content')
<section class="relative">
    <div class="relative h-[460px] sm:h-[520px] lg:h-[580px] overflow-hidden">
        <picture>
            <source srcset="{{ asset('images/hero-ferplec.webp') }}" type="image/webp">
            <img src="{{ asset('images/hero-ferplec.jpg') }}" alt="FERPLEC - Ferretería, Plomería y Electricidad. Todo para tus proyectos." class="absolute inset-0 w-full h-full object-cover object-[60%_center] lg:object-[55%_center]">
        </picture>
        <div class="absolute inset-0 bg-gradient-to-r from-[#173451] via-[#173451]/85 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#173451]/40 via-transparent to-transparent lg:hidden"></div>
        <div class="relative max-w-7xl mx-auto px-4 h-full flex items-center">
            <div class="max-w-xl lg:max-w-2xl text-white space-y-4 sm:space-y-5">
                <p class="uppercase tracking-[0.28em] sm:tracking-[0.35em] text-[10px] sm:text-xs lg:text-sm text-white/80">Ferretería • Plomería • Electricidad</p>
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black leading-[1.05]">Todo para tus proyectos.</h1>
                <p class="text-base sm:text-lg lg:text-xl text-white/90 leading-relaxed">Explora nuestros productos, arma tu lista y envíala por WhatsApp. Te asesoramos para encontrar justo lo que necesitas.</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-semibold text-white/90">Materiales que hacen posibles tus ideas.</p>
                <div class="flex flex-wrap gap-2 sm:gap-3 pt-2">
                    <a href="{{ route('catalog') }}" class="rounded-lg bg-[#E66A19] px-5 sm:px-6 py-2.5 font-semibold hover:bg-[#d15f14] active:scale-[0.98] transition-all shadow-lg shadow-black/10">Ver catálogo</a>
                    <a href="{{ route('cart') }}" class="rounded-lg bg-white/10 backdrop-blur px-5 sm:px-6 py-2.5 font-semibold hover:bg-white/20 active:scale-[0.98] transition-all">Armar mi lista</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 -mt-8 relative z-10">
    <div class="grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-3">
        @foreach($mainCategories as $category)
        <a href="{{ route('catalog', ['category' => $category->id]) }}" class="group relative overflow-hidden rounded-xl shadow-lg h-28 sm:h-32">
            <img src="{{ $category->image ? (str_starts_with($category->image, 'http') ? $category->image : asset('storage/'.$category->image)) : 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?q=80&w=1600&auto=format&fit=crop' }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover object-[70%_center] group-hover:scale-105 transition duration-500">
            <div class="absolute inset-0 bg-gradient-to-r from-[#173451]/95 via-[#173451]/65 to-transparent"></div>
            <div class="absolute inset-y-0 left-0 flex items-center p-4 sm:p-5">
                <div>
                    <h3 class="text-white text-lg sm:text-xl font-bold leading-tight">{{ $category->name }}</h3>
                    <p class="text-white/80 text-xs sm:text-sm mt-0.5">{{ $category->products_count }} productos</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>

@if($subcategories->count() > 0)
<section class="max-w-7xl mx-auto px-4 mt-10">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-xs sm:text-sm font-semibold uppercase tracking-[0.18em] text-gray-500">Explorar por subcategoría</h2>
        <a href="{{ route('catalog') }}" class="text-sm font-semibold text-[#E66A19] hover:text-[#d15f14] transition-colors whitespace-nowrap">Ver todo</a>
    </div>
    <div class="mt-3 flex gap-2 overflow-x-auto pb-1 -mx-4 px-4 sm:mx-0 sm:px-0 sm:flex-wrap sm:overflow-visible">
        @foreach($subcategories as $subcategory)
        <a href="{{ route('catalog', ['category' => $subcategory->id]) }}" class="shrink-0 inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-[#173451] hover:border-[#E66A19] hover:text-[#E66A19] hover:bg-[#E66A19]/5 transition-colors">
            {{ $subcategory->name }}
            <span class="text-xs font-normal text-gray-400">{{ $subcategory->subcategory_products_count }}</span>
        </a>
        @endforeach
    </div>
</section>
@endif

<section class="max-w-7xl mx-auto px-4 mt-12">
    <div class="flex items-end justify-between gap-3 mb-6">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-[#173451]">Productos destacados</h2>
            <p class="text-gray-600 hidden sm:block">Materiales seleccionados para tus proyectos</p>
        </div>
        <a href="{{ route('catalog') }}" class="text-[#E66A19] font-semibold whitespace-nowrap">Ver todo el catálogo</a>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        @forelse($featuredProducts as $product)
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden flex flex-col">
            <a href="{{ route('product.show', $product) }}" class="aspect-[4/3] bg-[#F4F6F8] block overflow-hidden">
                @if($product->main_image)
                    <img src="{{ asset('storage/'.$product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
            </a>
            <div class="p-3 sm:p-4 flex flex-col gap-1.5 sm:gap-2 flex-1">
                <div class="text-[10px] sm:text-xs text-gray-400">{{ $product->code }}</div>
                <a href="{{ route('product.show', $product) }}" class="text-sm sm:font-semibold text-[#173451] hover:text-[#E66A19] line-clamp-2 leading-snug">{{ $product->name }}</a>
                <p class="hidden sm:block text-sm text-gray-500 line-clamp-2">{{ $product->short_description }}</p>
                <div class="mt-auto pt-1 flex items-end justify-between gap-2">
                    <div>
                        <div class="font-bold text-[#173451] text-sm sm:text-base">{{ $product->price_formatted }}</div>
                        @if($product->unit !== 'unidad')
                            <div class="text-[10px] sm:text-xs text-gray-500">Unidad: {{ $product->unit }}</div>
                        @endif
                    </div>
                    <span class="text-[10px] sm:text-xs {{ $product->in_stock ? 'text-[#22A366]' : 'text-gray-400' }}">{{ $product->in_stock ? 'Disponible' : 'Sin stock' }}</span>
                </div>
                <button @click="$store.cart.add({code: '{{ $product->code }}', name: '{{ addslashes($product->name) }}', price: {{ $product->price ?? 0 }}, unit: '{{ $product->unit }}', variant: null, stock: {{ $product->stock }}})" class="w-full rounded-lg bg-[#173451] text-white py-1.5 sm:py-2 text-xs sm:text-sm hover:bg-[#132a44] transition-colors">Agregar a mi lista</button>
            </div>
        </div>
        @empty
        <p class="text-gray-500 col-span-full">No hay productos destacados aún.</p>
        @endforelse
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-12">
    <div class="rounded-2xl bg-[#F4F6F8] p-4 sm:p-6 md:p-10 grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="text-center space-y-1.5 md:space-y-2">
            <div class="w-9 h-9 md:w-12 md:h-12 mx-auto rounded-full bg-[#173451]/10 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 md:w-6 md:h-6 text-[#173451]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <h3 class="font-semibold text-[#173451] text-sm md:text-base leading-tight">Sin registro</h3>
            <p class="text-xs md:text-sm text-gray-600 leading-snug">Compra por lista sin crear cuenta</p>
        </div>
        <div class="text-center space-y-1.5 md:space-y-2">
            <div class="w-9 h-9 md:w-12 md:h-12 mx-auto rounded-full bg-[#22A366]/10 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 md:w-6 md:h-6 text-[#22A366]" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
            </div>
            <h3 class="font-semibold text-[#173451] text-sm md:text-base leading-tight">Atención directa por WhatsApp</h3>
            <p class="text-xs md:text-sm text-gray-600 leading-snug">Respuesta ágil y personalizada</p>
        </div>
        <div class="text-center space-y-1.5 md:space-y-2">
            <div class="w-9 h-9 md:w-12 md:h-12 mx-auto rounded-full bg-[#E66A19]/10 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 md:w-6 md:h-6 text-[#E66A19]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2c0-.656.126-1.283.356-1.857M7 20v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a3 3 0 11-4 0 3 3 0 014 0zM7 10a3 3 0 11-4 0 3 3 0 014 0z"/></svg>
            </div>
            <h3 class="font-semibold text-[#173451] text-sm md:text-base leading-tight">Asesoría personalizada</h3>
            <p class="text-xs md:text-sm text-gray-600 leading-snug">Te ayudamos a armar tu lista</p>
        </div>
        <div class="text-center space-y-1.5 md:space-y-2">
            <div class="w-9 h-9 md:w-12 md:h-12 mx-auto rounded-full bg-[#173451]/10 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 md:w-6 md:h-6 text-[#173451]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <h3 class="font-semibold text-[#173451] text-sm md:text-base leading-tight">Consulta de disponibilidad</h3>
            <p class="text-xs md:text-sm text-gray-600 leading-snug">Confirmamos precio y stock</p>
        </div>
    </div>
</section>

@if($tips->count() > 0)
<section class="max-w-7xl mx-auto px-4 mt-12" x-data="carousel">
    <div class="flex items-end justify-between gap-3 mb-5">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-[#173451]">Tips FERPLEC</h2>
            <p class="text-gray-600 hidden sm:block">Aprende, construye y soluciona</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('tips.index') }}" class="text-sm md:text-base text-[#E66A19] font-semibold whitespace-nowrap">Ver todos</a>
            <div class="hidden lg:flex items-center gap-1.5">
                <button type="button" @click="move(-1)" :disabled="atStart" class="w-9 h-9 rounded-full border border-gray-200 bg-white flex items-center justify-center text-[#173451] hover:border-[#E66A19] hover:text-[#E66A19] disabled:opacity-30 disabled:hover:border-gray-200 disabled:hover:text-[#173451] transition-colors" aria-label="Tip anterior">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" @click="move(1)" :disabled="atEnd" class="w-9 h-9 rounded-full border border-gray-200 bg-white flex items-center justify-center text-[#173451] hover:border-[#E66A19] hover:text-[#E66A19] disabled:opacity-30 disabled:hover:border-gray-200 disabled:hover:text-[#173451] transition-colors" aria-label="Tip siguiente">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    <div x-ref="track" class="flex gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth overscroll-x-contain -mx-4 px-4 lg:mx-0 lg:px-0 pb-2">
        @foreach($tips as $tip)
        <article class="snap-start shrink-0 w-[84%] sm:w-[52%] lg:w-[calc(33.333%-0.667rem)] rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            <a href="{{ route('tips.show', $tip) }}" class="block aspect-[16/9] bg-gradient-to-br from-[#173451] to-[#24507a] overflow-hidden">
                @if($tip->image)
                    <img src="{{ asset('storage/'.$tip->image) }}" alt="{{ $tip->title }}" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center text-white/25">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                @endif
            </a>
            <div class="p-4 flex flex-col gap-2 flex-1">
                <span class="text-[10px] uppercase tracking-widest text-[#E66A19]">{{ $tip->category }}</span>
                <a href="{{ route('tips.show', $tip) }}" class="font-semibold text-[#173451] leading-snug hover:text-[#E66A19] line-clamp-2">{{ $tip->title }}</a>
                <p class="text-sm text-gray-500 line-clamp-2">{{ $tip->excerpt }}</p>
                <div class="mt-auto pt-1 flex items-center justify-between text-xs text-gray-400">
                    <span>{{ $tip->read_time }} min lectura</span>
                    <a href="{{ route('tips.show', $tip) }}" class="font-semibold text-[#173451] hover:text-[#E66A19]">Leer más →</a>
                </div>
            </div>
        </article>
        @endforeach
    </div>
</section>
@endif
@endsection

