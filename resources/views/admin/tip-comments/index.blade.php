@extends('layouts.admin')

@section('admin-title', 'Comentarios de tips')

@section('admin-content')
<div class="flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-black text-slate-900">Comentarios de tips</h1>
        <p class="mt-1 text-sm text-slate-500">Aprueba o elimina comentarios antes de mostrarlos</p>
    </div>
</div>

@if (session('status'))
    <div class="mt-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
@endif

{{-- Tarjetas móviles --}}
<div class="mt-4 space-y-3 lg:hidden">
    @forelse ($comments as $comment)
        <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3 min-w-0">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-50 text-sm font-bold text-brand-700">{{ strtoupper(mb_substr($comment->name, 0, 1)) }}</div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900">{{ $comment->name }} @if($comment->email)<span class="text-xs text-slate-400">({{ $comment->email }})</span>@endif</p>
                        <p class="mt-0.5 text-xs text-slate-400">{{ $comment->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @if($comment->is_approved)
                    <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">Publicado</span>
                @else
                    <span class="shrink-0 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700">Pendiente</span>
                @endif
            </div>
            <p class="mt-2 whitespace-pre-line text-sm text-slate-600">{{ $comment->content }}</p>
            @if($comment->tip)
                <a href="{{ route('tips.show', $comment->tip) }}" target="_blank" class="mt-2 inline-block text-xs font-medium text-brand-700">{{ $comment->tip->title }}</a>
            @else
                <p class="mt-2 text-xs text-slate-400">Tip eliminado</p>
            @endif
            <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3">
                @if(!$comment->is_approved)
                    <form method="POST" action="{{ route('admin.tip-comments.approve', $comment) }}">
                        @csrf
                        <button class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white">Aprobar</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.tip-comments.reject', $comment) }}">
                        @csrf
                        <button class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">Ocultar</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.tip-comments.destroy', $comment) }}" onsubmit="return confirm('¿Eliminar este comentario?')">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-600">Eliminar</button>
                </form>
            </div>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-10 text-center text-sm text-slate-400">No hay comentarios.</div>
    @endforelse

    @if($comments->hasPages())
        <div class="pt-2">{{ $comments->links() }}</div>
    @endif
</div>

{{-- Tabla desktop --}}
<div class="mt-6 hidden overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm lg:block">
    <table class="min-w-full divide-y divide-slate-100 text-sm">
        <thead class="bg-slate-50/70">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Comentario</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Tip</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($comments as $comment)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-6 py-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-50 text-sm font-bold text-brand-700">{{ strtoupper(mb_substr($comment->name, 0, 1)) }}</div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $comment->name }} @if($comment->email)<span class="ml-1 text-xs text-slate-400">({{ $comment->email }})</span>@endif</p>
                                <p class="mt-1 whitespace-pre-line text-slate-600">{{ $comment->content }}</p>
                                <p class="mt-2 text-xs text-slate-400">{{ $comment->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($comment->tip)
                            <a href="{{ route('tips.show', $comment->tip) }}" target="_blank" class="font-medium text-slate-700 hover:text-brand-700">{{ $comment->tip->title }}</a>
                        @else
                            <span class="text-slate-400">Eliminado</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($comment->is_approved)
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Publicado</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">Pendiente</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            @if(!$comment->is_approved)
                                <form method="POST" action="{{ route('admin.tip-comments.approve', $comment) }}">
                                    @csrf
                                    <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">Aprobar</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.tip-comments.reject', $comment) }}">
                                    @csrf
                                    <button class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">Ocultar</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.tip-comments.destroy', $comment) }}" onsubmit="return confirm('¿Eliminar este comentario?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">No hay comentarios.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($comments->hasPages())
        <div class="border-t border-slate-100 px-6 py-4">
            {{ $comments->links() }}
        </div>
    @endif
</div>
@endsection
