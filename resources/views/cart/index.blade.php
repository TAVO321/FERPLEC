@extends('layouts.app')

@section('title', 'Mi lista de materiales | FERPLEC')

@section('content')
<section class="bg-[#173451] text-white">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl md:text-4xl font-bold">Mi lista de materiales</h1>
        <p class="text-white/80 mt-2">Arma tu lista y envíala por WhatsApp para recibir cotización</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-8 grid gap-8 lg:grid-cols-[1fr_380px]">
    <div class="space-y-4">
        <template x-if="$store.cart.items.length === 0">
            <div class="rounded-2xl border border-gray-100 p-8 text-center space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-[#F4F6F8] flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h2 class="text-xl font-semibold text-[#173451]">Tu lista está vacía</h2>
                <p class="text-gray-600">Explora el catálogo y agrega los materiales que necesitas</p>
                <a href="{{ route('catalog') }}" class="inline-flex rounded-lg bg-[#E66A19] text-white px-6 py-2">Ir al catálogo</a>
            </div>
        </template>
        <template x-for="(item, index) in $store.cart.items" :key="index">
            <div class="rounded-2xl border border-gray-100 p-4 flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex-1">
                    <div class="font-semibold text-[#173451]" x-text="item.name"></div>
                    <div class="text-sm text-gray-500">Código: <span x-text="item.code"></span> • Unidad: <span x-text="item.unit"></span></div>
                    <div class="text-sm text-gray-500" x-text="item.price > 0 ? 'Bs ' + item.price.toFixed(2) : 'Consultar'"></div>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="$store.cart.updateQty(index, item.qty - 1)" class="w-8 h-8 rounded-lg border border-gray-200">-</button>
                    <span class="w-10 text-center" x-text="item.qty"></span>
                    <button @click="$store.cart.updateQty(index, item.qty + 1)" class="w-8 h-8 rounded-lg border border-gray-200">+</button>
                </div>
                <button @click="$store.cart.remove(index)" class="text-sm text-red-500 hover:underline">Eliminar</button>
            </div>
        </template>
    </div>
    <div class="space-y-4">
        <div class="rounded-2xl border border-gray-100 p-6 space-y-4">
            <h2 class="text-xl font-semibold text-[#173451]">Resumen</h2>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Productos</span>
                <span x-text="$store.cart.count()"></span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Subtotal de referencia</span>
                <span x-text="'Bs ' + $store.cart.subtotal().toFixed(2)"></span>
            </div>
            <p class="text-xs text-gray-500">La cotización final estará sujeta a confirmación.</p>
            <button x-show="$store.cart.items.length > 0" @click="sendToWhatsapp()" class="w-full rounded-lg bg-[#22A366] text-white py-3 font-semibold hover:bg-[#1c8a58]">Enviar lista por WhatsApp</button>
            <button x-show="$store.cart.items.length > 0" @click="if(confirm('¿Vaciar lista de materiales?')) $store.cart.clear()" class="w-full rounded-lg border border-gray-200 py-2 text-sm">Vaciar lista</button>
        </div>
        <div class="rounded-2xl border border-gray-100" x-data="{open:false}">
            <button @click="open = !open" class="w-full flex items-center justify-between p-4">
                <span class="font-semibold text-[#173451]">¿Cómo funciona mi lista de materiales?</span>
                <svg class="w-5 h-5 transition" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-cloak class="px-4 pb-4 space-y-2 text-sm text-gray-600">
                <p>1. Explora nuestro catálogo</p>
                <p>2. Agrega los materiales que necesitas</p>
                <p>3. Revisa y modifica las cantidades</p>
                <p>4. Envía toda tu lista por WhatsApp</p>
                <p>5. Recibe atención personalizada de FERPLEC</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function sendToWhatsapp() {
    const items = Alpine.store('cart').items;
    if (items.length === 0) return;
    let msg = 'Hola, FERPLEC. Me gustaría consultar disponibilidad y precios de los siguientes materiales:\n\nMI LISTA DE MATERIALES:\n';
    items.forEach((item, idx) => {
        msg += (idx+1) + '. ' + item.name;
        if (item.variant) msg += ' - ' + item.variant;
        msg += ' — ' + item.qty + ' ' + item.unit + '.\n';
    });
    msg += '\n¿Podrían confirmarme disponibilidad, precio total y opciones de entrega?\n\nMuchas gracias.';
    const url = '{{ wa_link() }}?text=' + encodeURIComponent(msg);
    window.open(url, '_blank');
}
</script>
@endpush
