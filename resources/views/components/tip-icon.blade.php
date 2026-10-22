@props(['category'])

@php
    $icon = match (mb_strtolower((string) $category)) {
        'electricidad' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>',
        'plomería', 'plomeria' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3.75s6 6.106 6 10.5a6 6 0 11-12 0c0-4.394 6-10.5 6-10.5z"/>',
        'ferretería', 'ferreteria' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17l-5.658 5.66a4.5 4.5 0 01-6.364-6.364l5.657-5.66m8.486-2.486a4.5 4.5 0 10-6.364 6.364l1.757-1.757m3.607-3.607l3.182 3.182a4.5 4.5 0 01-6.364 6.364l-3.182-3.182"/>',
        'construcción', 'construccion' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-12 0v12"/>',
        default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
    };
@endphp

<svg {{ $attributes->merge(['class' => 'w-6 h-6']) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
    {!! $icon !!}
</svg>
