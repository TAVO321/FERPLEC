@extends('layouts.admin')

@section('admin-title', 'Configuración')

@section('admin-content')
<div class="max-w-2xl">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-black text-slate-900">Configuración del sitio</h1>
    </div>

    @if (session('status'))
        <div class="mt-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-semibold text-slate-700">Número de WhatsApp *</label>
            <input name="whatsapp" value="{{ old('whatsapp', $whatsapp) }}" required placeholder="59163951262"
                   class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
            <p class="mt-1.5 text-xs text-slate-500">Formato: código país + número (sin +, sin espacios). Se usará en todo el sitio.</p>
            @error('whatsapp') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4">
            <label class="block text-sm font-semibold text-slate-700">Nombre del sitio</label>
            <input name="site_name" value="{{ old('site_name', $site_name) }}"
                   class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
            @error('site_name') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4">
            <label class="block text-sm font-semibold text-slate-700">Eslogan</label>
            <input name="site_slogan" value="{{ old('site_slogan', $site_slogan) }}"
                   class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:border-brand-500/20">
            @error('site_slogan') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mt-6">
            <button class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:text-brand-700">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection
