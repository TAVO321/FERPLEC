@extends('layouts.admin')

@section('admin-title', 'Editar categoría')

@section('admin-content')
<div class="max-w-xl">
    <h1 class="text-xl font-black text-slate-900">Editar {{ $category->name }}</h1>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="mt-6 rounded-2xl border border-slate-200 bg-white p-6">
        @csrf @method('PUT')
        @include('admin.categories.form', ['category' => $category])
        <div class="mt-6 flex gap-3">
            <button class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:text-brand-700">Actualizar</button>
            <a href="{{ route('admin.categories.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
