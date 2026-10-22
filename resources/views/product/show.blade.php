@extends('layouts.app')

@section('title', $product->name . ' | FERPLEC')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-8">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-[#E66A19]">Inicio</a> / <a href="{{ route('catalog') }}" class="hover:text-[#E66A19]">Productos</a> / <span class="text-gray-900">{{ $product->name }}</span>
    </nav>
    <div class="grid gap-8 lg:grid-cols-2">
        <div class="space-y-4">
            <div class="aspect-square rounded-2xl overflow-hidden bg-[#F4F6F8]">
                @if($product->main_image)
                    <img src="{{ media_url($product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
            </div>
        </div>
        <div class="space-y-6">
            <div>
                <div class="text-sm text-gray-500 mb-2">{{ $product->code }} @if($product->brand) • {{ $product->brand }} @endif</div>
                <h1 class="text-3xl md:text-4xl font-bold text-[#173451]">{{ $product->name }}</h1>
                <p class="text-gray-600 mt-3 leading-relaxed">{{ $product->short_description }}</p>
            </div>
            <div class="flex items-end gap-4">
                <div class="text-3xl font-bold text-[#173451]">{{ $product->price_formatted }}</div>
                <div class="text-gray-500">Unidad: {{ $product->unit }}</div>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $product->in_stock ? 'bg-[#22A366]' : 'bg-red-500' }}"></span>
                <span class="{{ $product->in_stock ? 'text-[#22A366]' : 'text-red-500' }}">{{ $product->in_stock ? 'Disponible' : 'No disponible - Consultar' }}</span>
            </div>
            <div class="flex flex-wrap gap-3">
                <button @click="$store.cart.add(@js(['code' => $product->code, 'name' => $product->name, 'price' => (float) ($product->price ?? 0), 'unit' => $product->unit, 'variant' => null, 'stock' => (int) $product->stock]))" class="rounded-lg bg-[#173451] text-white px-6 py-3 font-semibold hover:bg-[#132a44]">Agregar a mi lista</button>
                <a href="{{ wa_link('Hola, FERPLEC. Me gustaría consultar por: ' . $product->name . ' (Código: ' . $product->code . ')') }}" target="_blank" class="rounded-lg bg-[#22A366] text-white px-6 py-3 font-semibold hover:bg-[#1c8a58]">Consultar por WhatsApp</a>
            </div>
            @if($product->description)
            <div class="rounded-2xl border border-gray-100 p-6 space-y-3">
                <h2 class="text-xl font-semibold text-[#173451]">Descripción</h2>
                <div class="prose prose-sm max-w-none text-gray-600">{!! nl2br(e($product->description)) !!}</div>
            </div>
            @endif
            @if($product->specifications)
            <div class="rounded-2xl border border-gray-100 p-6 space-y-3">
                <h2 class="text-xl font-semibold text-[#173451]">Especificaciones</h2>
                <div class="prose prose-sm max-w-none text-gray-600">{!! nl2br(e($product->specifications)) !!}</div>
            </div>
            @endif
        </div>
    </div>
</section>

@if($related->count() > 0)
<section class="max-w-7xl mx-auto px-4 mt-12">
    <h2 class="text-2xl font-bold text-[#173451] mb-6">Productos relacionados</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($related as $item)
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
            <a href="{{ route('product.show', $item) }}" class="aspect-[4/3] bg-[#F4F6F8] block">
                @if($item->main_image)
                    <img src="{{ media_url($item->main_image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                @endif
            </a>
            <div class="p-4 space-y-2">
                <a href="{{ route('product.show', $item) }}" class="font-semibold text-[#173451] hover:text-[#E66A19] line-clamp-2">{{ $item->name }}</a>
                <div class="font-bold text-[#173451]">{{ $item->price_formatted }}</div>
                <button @click="$store.cart.add(@js(['code' => $item->code, 'name' => $item->name, 'price' => (float) ($item->price ?? 0), 'unit' => $item->unit, 'variant' => null, 'stock' => (int) $item->stock]))" class="w-full rounded-lg border border-[#173451] text-[#173451] py-2 hover:bg-[#173451] hover:text-white">Agregar a mi lista</button>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
@endsection
