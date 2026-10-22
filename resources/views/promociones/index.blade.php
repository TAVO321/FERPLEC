@extends('layouts.app')

@section('title', 'Promociones | FERPLEC')

@section('content')
<section class="bg-[#173451] text-white">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl md:text-4xl font-bold">Promociones</h1>
        <p class="text-white/80 mt-2">Banners y campañas vigentes</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-8 space-y-6">
    @forelse($banners as $banner)
    <a href="{{ $banner->button_link ?? '#' }}" class="block rounded-2xl overflow-hidden shadow-sm">
        <img src="{{ asset('storage/'.$banner->image) }}" alt="{{ $banner->title }}" class="w-full h-auto">
    </a>
    @empty
    <div class="text-center py-12 text-gray-500">No hay promociones vigentes por el momento.</div>
    @endforelse
</section>
@endsection
