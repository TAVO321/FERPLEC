@extends('layouts.admin')

@section('admin-title', 'Editar producto')

@section('admin-content')
<div class="max-w-2xl">
    <h1 class="text-xl font-black text-slate-900">Editar <span class="font-mono text-base text-slate-400">{{ $product->code }}</span></h1>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="mt-6 rounded-2xl border border-slate-200 bg-white p-6">
        @csrf @method('PUT')
        @include('admin.products.form', ['product' => $product])

        <div class="mt-6 flex gap-3">
            <button class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:text-brand-700">Actualizar producto</button>
            <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
