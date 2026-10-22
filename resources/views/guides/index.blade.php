@extends('layouts.app')

@section('title', 'Tips FERPLEC — Aprende, construye y soluciona')
@section('description', 'Guías prácticas de ferretería, plomería y electricidad: cómo elegir el material correcto, cómo instalar sin errores y cómo resolver los problemas más comunes del hogar.')

@section('content')
<section class="relative overflow-hidden bg-[#173451] text-white">
    <picture>
        <source srcset="{{ asset('storage/banners/tips-hero.webp') }}" type="image/webp">
        <img src="{{ asset('storage/banners/tips-hero.jpg') }}" alt="Tips FERPLEC - Aprende, construye y soluciona" class="absolute inset-0 w-full h-full object-cover object-[70%_center]">
    </picture>
    <div class="absolute inset-0 bg-gradient-to-r from-[#173451] via-[#173451]/95 to-[#173451]/55"></div>
    <div class="relative max-w-7xl mx-auto px-4 py-10 md:py-16">
        <nav class="text-xs sm:text-sm text-white/60 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Inicio</a>
            <span class="px-1">/</span>
            <span class="text-white/90">Tips FERPLEC</span>
        </nav>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[10px] sm:text-xs uppercase tracking-[0.2em] text-white/85">
            <x-tip-icon category="Electricidad" class="w-3.5 h-3.5" />
            Blog FERPLEC
        </span>
        <h1 class="mt-4 text-3xl md:text-5xl font-bold leading-tight">Tips FERPLEC</h1>
        <p class="mt-3 text-white/80 max-w-2xl text-sm md:text-lg leading-relaxed">Guías prácticas para que elijas bien el material, instales sin errores y resuelvas tú mismo lo más común del hogar.</p>
        <div class="mt-8 flex flex-wrap gap-8">
            <div>
                <div class="text-2xl md:text-3xl font-bold">{{ $totalTips }}</div>
                <div class="text-xs text-white/60 uppercase tracking-widest">Tips publicados</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-bold">{{ $categories->count() }}</div>
                <div class="text-xs text-white/60 uppercase tracking-widest">Categorías</div>
            </div>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-8">
    <div class="flex gap-2 overflow-x-auto pb-2 -mx-4 px-4 sm:mx-0 sm:px-0 sm:flex-wrap sm:overflow-visible">
        <a href="{{ route('tips.index') }}"
           class="shrink-0 inline-flex items-center gap-1.5 rounded-full border px-4 py-2 text-sm font-medium transition-colors {{ request('categoria') ? 'border-gray-200 bg-white text-[#173451] hover:border-[#E66A19] hover:text-[#E66A19]' : 'border-[#173451] bg-[#173451] text-white' }}">
            Todos
        </a>
        @foreach($categories as $category)
            <a href="{{ route('tips.index', ['categoria' => $category]) }}"
               class="shrink-0 inline-flex items-center gap-1.5 rounded-full border px-4 py-2 text-sm font-medium transition-colors {{ request('categoria') === $category ? 'border-[#173451] bg-[#173451] text-white' : 'border-gray-200 bg-white text-[#173451] hover:border-[#E66A19] hover:text-[#E66A19]' }}">
                <x-tip-icon :category="$category" class="w-4 h-4" />
                {{ $category }}
            </a>
        @endforeach
    </div>
</section>

@if($featured)
<section class="max-w-7xl mx-auto px-4 mt-8">
    <a href="{{ route('tips.show', $featured) }}" class="group grid gap-0 md:grid-cols-2 rounded-3xl overflow-hidden border border-gray-100 bg-[#F4F6F8] hover:shadow-xl transition-shadow">
        <div class="relative aspect-[16/9] md:aspect-auto md:min-h-[300px] bg-gradient-to-br from-[#173451] via-[#1d4266] to-[#24507a] flex items-center justify-center text-white/25">
            <x-tip-icon :category="$featured->category" class="w-16 h-16" />
            <span class="absolute top-4 left-4 rounded-full bg-[#E66A19] px-3 py-1 text-[10px] uppercase tracking-widest text-white font-semibold">Destacado</span>
        </div>
        <div class="p-6 md:p-9 flex flex-col justify-center">
            <span class="text-xs uppercase tracking-widest text-[#E66A19]">{{ $featured->category }}</span>
            <h2 class="mt-2 text-2xl md:text-3xl font-bold text-[#173451] leading-tight group-hover:text-[#E66A19] transition-colors">{{ $featured->title }}</h2>
            <p class="mt-3 text-gray-600 leading-relaxed">{{ $featured->excerpt }}</p>
            <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                <span>{{ $featured->published_at?->format('d/m/Y') }}</span>
                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                <span>{{ $featured->read_time }} min de lectura</span>
                <span class="ml-auto font-semibold text-[#E66A19]">Leer el tip →</span>
            </div>
        </div>
    </a>
</section>
@endif

<section class="max-w-7xl mx-auto px-4 mt-10">
    <div class="flex items-baseline justify-between gap-3 mb-5">
        <h2 class="text-xl md:text-2xl font-bold text-[#173451]">
            {{ request('categoria') ? request('categoria') : 'Todos los tips' }}
        </h2>
        @if(request('categoria'))
            <a href="{{ route('tips.index') }}" class="text-sm font-semibold text-[#E66A19] hover:text-[#d15f14] whitespace-nowrap">Quitar filtro</a>
        @endif
    </div>

    <div class="grid gap-4 sm:gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($tips as $tip)
        <article class="group flex flex-col rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-0.5 transition-all">
            <a href="{{ route('tips.show', $tip) }}" class="relative aspect-[16/9] bg-gradient-to-br from-[#173451] via-[#1d4266] to-[#24507a] flex items-center justify-center text-white/25 overflow-hidden">
                @if($tip->image)
                    <img src="{{ asset('storage/'.$tip->image) }}" alt="{{ $tip->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                @else
                    <x-tip-icon :category="$tip->category" class="w-12 h-12" />
                @endif
                <span class="absolute top-3 left-3 rounded-full bg-white/15 backdrop-blur px-2.5 py-1 text-[10px] uppercase tracking-widest text-white">{{ $tip->category }}</span>
            </a>
            <div class="p-5 flex flex-col gap-2 flex-1">
                <h3 class="text-lg font-semibold text-[#173451] leading-snug">
                    <a href="{{ route('tips.show', $tip) }}" class="hover:text-[#E66A19] transition-colors">{{ $tip->title }}</a>
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">{{ $tip->excerpt }}</p>
                <div class="mt-auto pt-3 flex items-center justify-between text-xs text-gray-400">
                    <span>{{ $tip->published_at?->format('d/m/Y') }} · {{ $tip->read_time }} min</span>
                    <span class="font-semibold text-[#173451] group-hover:text-[#E66A19] transition-colors">Leer más →</span>
                </div>
            </div>
        </article>
        @empty
        <div class="col-span-full text-center py-16">
            <div class="w-14 h-14 mx-auto rounded-full bg-[#F4F6F8] flex items-center justify-center text-gray-300">
                <x-tip-icon category="" class="w-7 h-7" />
            </div>
            <p class="mt-4 text-gray-500">No hay tips publicados en esta categoría todavía.</p>
            <a href="{{ route('tips.index') }}" class="inline-block mt-4 text-sm font-semibold text-[#E66A19]">Ver todos los tips</a>
        </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $tips->links('partials.pagination') }}
    </div>
</section>
@endsection
