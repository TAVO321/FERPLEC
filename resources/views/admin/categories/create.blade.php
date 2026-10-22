@extends('layouts.admin')

@section('admin-title', 'Nueva categoría')

@section('admin-content')
<div class="max-w-xl">
    <h1 class="text-xl font-black text-slate-900">Nueva categoría</h1>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-6 rounded-2xl border border-slate-200 bg-white p-6">
        @csrf
        @include('admin.categories.form')
        <div class="mt-6 flex gap-3">
            <button class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:text-brand-700">Guardar</button>
            <a href="{{ route('admin.categories.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
