@extends('layouts.app')

@section('title', $tip->title . ' | Tips FERPLEC')
@section('description', $tip->excerpt ?: 'Guía práctica de FERPLEC sobre ferretería, plomería y electricidad.')

@section('content')
<article>
    <header class="relative overflow-hidden bg-gradient-to-br from-[#173451] via-[#1d4266] to-[#24507a] text-white">
        <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 22px 22px;"></div>
        <div class="relative max-w-4xl mx-auto px-4 pt-8 pb-10 md:pt-10 md:pb-14">
            <nav class="text-xs sm:text-sm text-white/60 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Inicio</a>
                <span class="px-1">/</span>
                <a href="{{ route('tips.index') }}" class="hover:text-white transition-colors">Tips FERPLEC</a>
                <span class="px-1">/</span>
                <span class="text-white/90 line-clamp-1">{{ $tip->title }}</span>
            </nav>

            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-[10px] sm:text-xs uppercase tracking-[0.2em] text-white/90">
                    <x-tip-icon :category="$tip->category" class="w-3.5 h-3.5" />
                    {{ $tip->category }}
                </span>
                <span class="text-xs text-white/60">{{ $tip->published_at?->format('d/m/Y') }}</span>
                <span class="w-1 h-1 rounded-full bg-white/40"></span>
                <span class="text-xs text-white/60">{{ $tip->read_time }} min de lectura</span>
            </div>

            <h1 class="mt-4 text-2xl sm:text-3xl md:text-4xl font-bold leading-tight">{{ $tip->title }}</h1>

            @if($tip->excerpt)
                <p class="mt-4 text-white/80 text-base md:text-lg leading-relaxed max-w-3xl">{{ $tip->excerpt }}</p>
            @endif
        </div>
    </header>

    <div class="max-w-3xl mx-auto px-4">
        <div class="-mt-8 md:-mt-12 relative z-10">
            @if($tip->image)
                <div class="rounded-2xl overflow-hidden aspect-[16/9] shadow-xl ring-1 ring-gray-100 bg-white">
                    <img src="{{ asset('storage/'.$tip->image) }}" alt="{{ $tip->title }}" class="w-full h-full object-cover">
                </div>
            @else
                <div class="rounded-2xl aspect-[16/9] shadow-xl ring-1 ring-gray-100 bg-gradient-to-br from-[#173451] via-[#1d4266] to-[#24507a] flex items-center justify-center text-white/20">
                    <x-tip-icon :category="$tip->category" class="w-20 h-20" />
                </div>
            @endif
        </div>

        <div class="tip-article prose prose-lg max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-headings:text-[#173451] prose-p:text-gray-700 prose-li:text-gray-700 prose-strong:text-[#173451] prose-a:text-[#E66A19] prose-a:no-underline hover:prose-a:underline mt-8">
            {!! nl2br(e($tip->content)) !!}
        </div>

        <div class="mt-10 rounded-2xl border border-gray-100 bg-[#F4F6F8] p-5 flex flex-wrap items-center gap-3" x-data="{ copied: false }">
            <span class="font-semibold text-[#173451]">¿Te sirvió este tip?</span>
            <span class="text-sm text-gray-500 hidden sm:inline">Compártelo o consúltanos por WhatsApp.</span>
            <div class="ml-auto flex items-center gap-2">
                <a href="https://wa.me/?text={{ rawurlencode($tip->title.' — '.url()->current()) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 rounded-lg bg-[#22A366] px-3 py-2 text-xs font-medium text-white hover:brightness-105 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                    WhatsApp
                </a>
                <button type="button" @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-[#173451] hover:border-[#E66A19] hover:text-[#E66A19] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                    <span x-text="copied ? '¡Copiado!' : 'Copiar link'"></span>
                </button>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row sm:items-center gap-4 rounded-2xl bg-[#173451] text-white p-6">
            <div class="flex-1">
                <h2 class="text-lg font-bold">¿Tienes dudas o necesitas el material?</h2>
                <p class="text-white/75 text-sm mt-1">Arma tu lista y te confirmamos precio y stock por WhatsApp.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('catalog') }}" class="rounded-lg bg-white/10 hover:bg-white/20 px-4 py-2.5 text-sm font-medium transition-colors">Ver catálogo</a>
                <a href="https://wa.me/{{ config('ferplec.whatsapp', env('WHATSAPP_NUMBER','59163951262')) }}" target="_blank" rel="noopener" class="rounded-lg bg-[#22A366] hover:brightness-105 px-4 py-2.5 text-sm font-medium transition">Consultar</a>
            </div>
        </div>
    </div>

    @if($relatedTips->count() > 0)
    <section class="max-w-7xl mx-auto px-4 mt-14">
        <div class="flex items-baseline justify-between gap-3 mb-5">
            <h2 class="text-xl md:text-2xl font-bold text-[#173451]">Más tips de {{ $tip->category }}</h2>
            <a href="{{ route('tips.index', ['categoria' => $tip->category]) }}" class="text-sm font-semibold text-[#E66A19] hover:text-[#d15f14] whitespace-nowrap">Ver todos</a>
        </div>
        <div class="grid gap-4 sm:gap-5 md:grid-cols-2 lg:grid-cols-4">
            @foreach($relatedTips as $related)
            <a href="{{ route('tips.show', $related) }}" class="group flex flex-col rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all">
                <div class="relative aspect-[16/9] bg-gradient-to-br from-[#173451] via-[#1d4266] to-[#24507a] flex items-center justify-center text-white/25 overflow-hidden">
                    @if($related->image)
                        <img src="{{ asset('storage/'.$related->image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <x-tip-icon :category="$related->category" class="w-10 h-10" />
                    @endif
                </div>
                <div class="p-4 flex flex-col gap-1.5 flex-1">
                    <span class="text-[10px] uppercase tracking-widest text-[#E66A19]">{{ $related->category }}</span>
                    <h3 class="font-semibold text-[#173451] leading-snug group-hover:text-[#E66A19] transition-colors line-clamp-2">{{ $related->title }}</h3>
                    <p class="text-sm text-gray-500 line-clamp-2">{{ $related->excerpt }}</p>
                    <span class="mt-auto pt-2 text-xs text-gray-400">{{ $related->read_time }} min lectura</span>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    @if($relatedProducts->count() > 0)
    <section class="max-w-7xl mx-auto px-4 mt-14">
        <h2 class="text-xl md:text-2xl font-bold text-[#173451] mb-5">Productos relacionados</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($relatedProducts as $product)
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden flex flex-col">
                <a href="{{ route('product.show', $product) }}" class="aspect-[4/3] bg-[#F4F6F8] block">
                    @if($product->main_image)
                        <img src="{{ asset('storage/'.$product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </a>
                <div class="p-4 flex flex-col gap-2 flex-1">
                    <div class="text-xs text-gray-400">{{ $product->code }}</div>
                    <a href="{{ route('product.show', $product) }}" class="font-semibold text-[#173451] hover:text-[#E66A19] line-clamp-2">{{ $product->name }}</a>
                    <div class="mt-auto flex items-center justify-between gap-2">
                        <span class="font-bold text-[#173451]">{{ $product->price_formatted }}</span>
                        <span class="text-xs {{ $product->in_stock ? 'text-[#22A366]' : 'text-gray-400' }}">{{ $product->in_stock ? 'Disponible' : 'Sin stock' }}</span>
                    </div>
                    <button @click="$store.cart.add({code: '{{ $product->code }}', name: '{{ addslashes($product->name) }}', price: {{ $product->price ?? 0 }}, unit: '{{ $product->unit }}', variant: null, stock: {{ $product->stock }}})" class="w-full rounded-lg bg-[#173451] text-white py-2 text-sm hover:bg-[#132a44] transition-colors">Agregar a mi lista</button>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <section class="max-w-4xl mx-auto px-4 mt-14 mb-10">
        <div class="rounded-3xl border border-gray-100 bg-white shadow-sm overflow-hidden">
            <div class="px-6 py-6 md:px-10 md:py-8 border-b border-gray-100">
                <h2 class="text-xl md:text-2xl font-bold text-[#173451]">Comentarios</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $comments->count() }} comentario{{ $comments->count() === 1 ? '' : 's' }} publicado{{ $comments->count() === 1 ? '' : 's' }}</p>
            </div>
            <div class="px-6 py-6 md:px-10 md:py-8 space-y-6">
                @if(session('comment_status'))
                    <div class="rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('comment_status') }}</div>
                @endif

                @forelse($comments as $comment)
                    <div class="flex gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#173451]/10 text-sm font-bold text-[#173451]">
                            {{ strtoupper(mb_substr($comment->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-semibold text-[#173451]">{{ $comment->name }}</p>
                                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $comment->content }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Aún no hay comentarios. Sé el primero en opinar.</p>
                @endforelse

                <div class="rounded-2xl border border-gray-100 bg-[#F8FAFC] p-6">
                    <h3 class="text-lg font-semibold text-[#173451]">Deja un comentario</h3>
                    <p class="mt-1 text-sm text-gray-500">Tu comentario quedará pendiente de aprobación.</p>
                    <form method="POST" action="{{ route('tips.comments.store', $tip) }}" class="mt-5 space-y-4">
                        @csrf
                        <input type="text" name="honeypot" class="hidden" tabindex="-1" autocomplete="off">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                                <input name="name" value="{{ old('name') }}" required class="mt-1.5 w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-[#E66A19] focus:outline-none focus:ring-2 focus:ring-[#E66A19]/10">
                                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Correo (opcional)</label>
                                <input name="email" type="email" value="{{ old('email') }}" class="mt-1.5 w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-[#E66A19] focus:outline-none focus:ring-2 focus:ring-[#E66A19]/10">
                                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Comentario *</label>
                            <textarea name="content" rows="4" required class="mt-1.5 w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-[#E66A19] focus:outline-none focus:ring-2 focus:ring-[#E66A19]/10">{{ old('content') }}</textarea>
                            @error('content') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <button class="rounded-xl bg-[#173451] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#132a44]">Enviar comentario</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</article>
@endsection
