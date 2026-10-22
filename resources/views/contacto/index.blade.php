@extends('layouts.app')

@section('title', 'Contacto | FERPLEC')

@section('content')
<section class="bg-[#173451] text-white">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl md:text-4xl font-bold">Contacto</h1>
        <p class="text-white/80 mt-2">Atención directa por WhatsApp</p>
    </div>
</section>

<section class="max-w-4xl mx-auto px-4 mt-8">
    <div class="rounded-2xl border border-gray-100 p-8 text-center space-y-4">
        <h2 class="text-2xl font-semibold text-[#173451]">¿Necesitas asesoría o cotización?</h2>
        <p class="text-gray-600">Sin registro. Te atendemos directamente.</p>
        <a href="https://wa.me/{{ config('ferplec.whatsapp', env('WHATSAPP_NUMBER','59163951262')) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-[#22A366] text-white px-8 py-3 font-semibold">Contactar por WhatsApp</a>
    </div>
</section>
@endsection
