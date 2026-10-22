<footer class="bg-[#173451] text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 py-12 grid gap-8 md:grid-cols-2 lg:grid-cols-4">
        <div class="space-y-4">
            <div class="flex flex-col">
                <span class="text-2xl font-black leading-none"><span class="text-white">FER</span><span class="text-[#E66A19]">PLEC</span></span>
                <span class="text-[8px] uppercase tracking-[0.22em] text-white/60 mt-1">Ferretería • Plomería • Electricidad</span>
            </div>
            <p class="text-white/80 text-sm leading-relaxed">Ferretería, Plomería y Electricidad fácil. Todo para tus proyectos. Materiales que hacen posibles tus ideas.</p>
        </div>
        <div>
            <h3 class="font-semibold mb-3">Navegación</h3>
            <ul class="space-y-2 text-sm text-white/80">
                <li><a href="{{ route('home') }}" class="hover:text-white">Inicio</a></li>
                <li><a href="{{ route('catalog') }}" class="hover:text-white">Productos</a></li>
                <li><a href="{{ route('tips.index') }}" class="hover:text-white">Tips</a></li>
            </ul>
        </div>
        <div>
            <h3 class="font-semibold mb-3">Atención</h3>
            <ul class="space-y-2 text-sm text-white/80">
                <li><a href="{{ route('cart') }}" class="hover:text-white">Mi lista de materiales</a></li>
                <li><a href="{{ wa_link() }}" target="_blank" class="hover:text-white">Consultar por WhatsApp</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white">Contacto</a></li>
            </ul>
        </div>
        <div>
            <h3 class="font-semibold mb-3">FERPLEC</h3>
            <p class="text-sm text-white/80 leading-relaxed">Sin registro. Atención directa por WhatsApp. Asesoría personalizada.</p>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col items-center justify-between gap-2 text-center text-sm text-white/40 sm:flex-row sm:text-left">
            <span>© {{ date('Y') }} FERPLEC. Todos los derechos reservados.</span>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-md bg-white/10 px-2 py-0.5 text-xs font-semibold text-white/80 transition-colors hover:bg-white/20" title="Panel de administración">Panel</a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-base font-bold text-white/95 shadow-sm transition-all hover:bg-white/40 sm:h-6 sm:w-6 sm:text-sm" title="Acceso" aria-label="Acceso">•</a>
                @endauth
            </div>
        </div>
    </div>
</footer>
