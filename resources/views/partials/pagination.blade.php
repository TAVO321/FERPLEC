@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginación" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
        <p class="text-sm text-gray-500">
            Mostrando
            <span class="font-semibold text-[#173451]">{{ $paginator->firstItem() }}</span>
            a
            <span class="font-semibold text-[#173451]">{{ $paginator->lastItem() }}</span>
            de
            <span class="font-semibold text-[#173451]">{{ $paginator->total() }}</span>
        </p>

        <div class="-mx-4 flex items-center gap-1 overflow-x-auto px-4 pb-1 sm:mx-0 sm:px-0 sm:pb-0">
            @if ($paginator->onFirstPage())
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-gray-100 text-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-[#173451] hover:border-[#E66A19] hover:text-[#E66A19] transition-colors" aria-label="Página anterior">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-gray-100 text-gray-300">…</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="flex h-9 min-w-9 shrink-0 items-center justify-center rounded-lg bg-[#173451] px-3 text-sm font-semibold text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="flex h-9 min-w-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 px-3 text-sm font-medium text-[#173451] hover:border-[#E66A19] hover:text-[#E66A19] transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-[#173451] hover:border-[#E66A19] hover:text-[#E66A19] transition-colors" aria-label="Página siguiente">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-gray-100 text-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
