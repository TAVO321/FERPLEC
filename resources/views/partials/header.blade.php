<header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100" x-data="{open:false}">
    <div class="max-w-[1400px] mx-auto px-2 sm:px-3">
        <div class="flex items-center gap-1 sm:gap-2 h-14">
            <a href="{{ route('home') }}" class="shrink-0 flex flex-col items-center leading-[0.9] group">
                <span class="text-[30px] font-black tracking-tight transition-transform group-hover:scale-[1.02]">
                    <span class="text-[#173451]">FER</span><span class="text-[#E66A19]">PLEC</span>
                </span>
                <span class="mt-[2px] text-[6px] uppercase tracking-[0.12em] text-gray-500 whitespace-nowrap group-hover:text-gray-600 transition-colors">
                    Ferretería • Plomería • Electricidad
                </span>
            </a>

            <nav class="hidden lg:flex items-center gap-1 ml-1 xl:ml-2">
                <a href="{{ route('home') }}" class="px-3 py-1.5 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-[#E66A19] transition-all">Inicio</a>
                <a href="{{ route('catalog') }}" class="px-3 py-1.5 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-[#E66A19] transition-all">Productos</a>
                <a href="{{ route('tips.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-[#E66A19] transition-all">Tips</a>
            </nav>

            <form action="{{ route('catalog') }}" method="GET" class="hidden md:flex flex-1 items-center min-w-0 mx-1">
                <input type="search" name="q" placeholder="Buscar..." class="w-full h-9 rounded-l-lg border border-gray-200 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#E66A19]/20 focus:border-[#E66A19] transition-all">
                <button class="h-9 rounded-r-lg bg-[#173451] text-white px-4 text-sm font-medium hover:bg-[#0f243a] active:scale-[0.98] transition-all">Buscar</button>
            </form>

            <div class="flex items-center gap-1 sm:gap-1.5 ml-auto">
                <a href="{{ route('cart') }}" class="relative flex items-center justify-center h-9 w-9 rounded-lg border border-gray-200 hover:border-[#E66A19] hover:bg-[#E66A19]/5 active:scale-95 transition-all">
                    <svg class="w-5 h-5 text-[#173451]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-[#22A366] text-white text-[9px] leading-none rounded-full px-1 shadow-sm" x-text="$store.cart.count()">0</span>
                </a>
                <a href="https://wa.me/{{ config('ferplec.whatsapp', env('WHATSAPP_NUMBER','59163951262')) }}" target="_blank" class="flex items-center justify-center gap-1.5 h-9 rounded-lg bg-gradient-to-r from-[#22A366] to-[#1f9a5f] text-white px-2.5 sm:px-3 shadow-sm hover:shadow-md hover:brightness-105 active:scale-[0.98] transition-all">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    </svg>
                    <span class="text-xs sm:text-sm font-medium">Contacto</span>
                </a>
                <button @click="open = !open" class="lg:hidden flex items-center justify-center h-9 w-9 rounded-lg border border-gray-200 hover:bg-gray-50 active:scale-95 transition-all">
                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-cloak x-transition class="lg:hidden border-t border-gray-100 bg-white/95 backdrop-blur">
        <nav class="flex flex-col">
            <a href="{{ route('home') }}" class="px-4 py-2.5 text-sm hover:bg-gray-50">Inicio</a>
            <a href="{{ route('catalog') }}" class="px-4 py-2.5 text-sm hover:bg-gray-50">Productos</a>
            <a href="{{ route('tips.index') }}" class="px-4 py-2.5 text-sm hover:bg-gray-50">Tips</a>
        </nav>
        <form action="{{ route('catalog') }}" method="GET" class="flex p-3 gap-2">
            <input type="search" name="q" placeholder="Buscar..." class="w-full h-9 rounded-lg border border-gray-200 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-[#E66A19]">
            <button class="h-9 rounded-lg bg-[#173451] text-white px-4 text-sm">Buscar</button>
        </form>
    </div>
</header>
