<nav class="bg-[#691C32] border-b border-[#4d0a1f] shadow-md text-white relative z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">
            <div class="flex items-center z-10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/logo_conabio_transparente.png') }}"
                        alt="Logo CONABIO"
                        class="h-10 w-auto object-contain brightness-0 invert drop-shadow"
                    />
                </a>
            </div>
            <div class="flex items-center gap-3 z-20">
                <details class="relative group">
                    <summary class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#4d0a1f] hover:bg-[#380616] border border-white/20 text-white shadow-sm transition cursor-pointer select-none list-none [&::-webkit-details-marker]:hidden">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>{{ Auth::user()->nombre ?? Auth::user()->name }}</span>
                        <svg class="w-3.5 h-3.5 text-[#D4C19C] transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>

                    <div class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-2xl border border-gray-200 py-2 z-50 text-gray-800">
                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Sesión Activa</p>
                            <p class="text-xs font-bold text-gray-900 truncate">
                                {{ Auth::user()->nombre ?? Auth::user()->name }} {{ Auth::user()->apellidoP ?? '' }}
                            </p>
                            <p class="text-[11px] text-gray-500 truncate">
                                {{ Auth::user()->correo ?? Auth::user()->email }}
                            </p>
                        </div>

                        <div class="p-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-red-600 hover:bg-red-50 hover:text-red-700 transition cursor-pointer text-left"
                                >
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </details>
            </div>
        </div>
    </div>
</nav>
