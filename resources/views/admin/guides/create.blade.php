@extends('layouts.admin')

@section('admin-title', 'Nueva guía')

@section('admin-content')
<div class="max-w-3xl">
    <h1 class="text-xl font-black text-slate-900">Nueva guía</h1>
    <form method="POST" action="{{ route('admin.tips.store') }}" enctype="multipart/form-data" class="mt-6 rounded-2xl border border-slate-200 bg-white p-6">
        @csrf
        @include('admin.guides.form')
        <div class="mt-6 flex gap-3">
            <button class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:text-brand-700">Guardar guía</button>
            <a href="{{ route('admin.tips.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
