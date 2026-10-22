@extends('layouts.app')

@section('title', '¿No sabes qué material necesitas? | FERPLEC')

@section('content')
<section class="bg-[#173451] text-white">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl md:text-4xl font-bold">¿No sabes qué material necesitas? Te ayudamos.</h1>
        <p class="text-white/80 mt-2">Asesoría humana directa por WhatsApp</p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-4 mt-8 grid gap-4 md:grid-cols-2">
    <a href="https://wa.me/{{ config('ferplec.whatsapp', env('WHATSAPP_NUMBER','59163951262')) }}?text={{ rawurlencode('Hola, FERPLEC. Necesito ayuda con electricidad. ¿Podrían asesorarme sobre qué materiales necesito?') }}" target="_blank" class="rounded-2xl border border-gray-100 p-6 hover:shadow-md hover:border-[#E66A19]/40 transition">
        <h2 class="text-xl font-semibold text-[#173451]">Necesito ayuda con electricidad</h2>
        <p class="text-gray-600 mt-2">Acometidas, cableado, tableros y más</p>
    </a>
    <a href="https://wa.me/{{ config('ferplec.whatsapp', env('WHATSAPP_NUMBER','59163951262')) }}?text={{ rawurlencode('Hola, FERPLEC. Necesito ayuda con plomería. ¿Podrían asesorarme sobre qué materiales necesito?') }}" target="_blank" class="rounded-2xl border border-gray-100 p-6 hover:shadow-md hover:border-[#E66A19]/40 transition">
        <h2 class="text-xl font-semibold text-[#173451]">Necesito ayuda con plomería</h2>
        <p class="text-gray-600 mt-2">Tuberías, sanitarios, grifería y más</p>
    </a>
    <a href="https://wa.me/{{ config('ferplec.whatsapp', env('WHATSAPP_NUMBER','59163951262')) }}?text={{ rawurlencode('Hola, FERPLEC. Necesito ayuda con construcción. ¿Podrían asesorarme sobre qué materiales necesito?') }}" target="_blank" class="rounded-2xl border border-gray-100 p-6 hover:shadow-md hover:border-[#E66A19]/40 transition">
        <h2 class="text-xl font-semibold text-[#173451]">Necesito ayuda con construcción</h2>
        <p class="text-gray-600 mt-2">Albañilería, acabados y concreto</p>
    </a>
    <a href="https://wa.me/{{ config('ferplec.whatsapp', env('WHATSAPP_NUMBER','59163951262')) }}?text={{ rawurlencode('Hola, FERPLEC. Tengo otra consulta y necesito asesoría. ¿Podrían ayudarme?') }}" target="_blank" class="rounded-2xl border border-gray-100 p-6 hover:shadow-md hover:border-[#E66A19]/40 transition">
        <h2 class="text-xl font-semibold text-[#173451]">Tengo otra consulta</h2>
        <p class="text-gray-600 mt-2">Cuéntanos tu necesidad y te asesoramos</p>
    </a>
</section>
@endsection
