<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script> pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';</script>
    @php
    $idEmpleadoActual = Auth::user()->idEmpleado ?? Auth::id();
    $cursosCatalogo = $cursosCatalogo ?? \Illuminate\Support\Facades\DB::table('cat_cursoSemarnat')->get();
    $todosLosCursos = \App\Models\Curso::where('idEmpleado', $idEmpleadoActual)->get();
    $otrosCursos = $todosLosCursos->where('obligatorio', 0);
    $cursosBD = $otrosCursos;
    $obligatoriosEntregados = $todosLosCursos->where('obligatorio', 1)->pluck('nombre')->toArray();
    $obligatoriosCompletados = count($obligatoriosEntregados);
    $horasActuales = $todosLosCursos->sum('horas');
    $metaHoras = 40;
    $porcentajeHoras = min(100, max(0, round(($horasActuales / $metaHoras) * 100)));
    $obtenerIcono = function($nombre) {
        $nombre = mb_strtolower($nombre, 'UTF-8');
        if (str_contains($nombre, 'ciberseguridad')) return '🛡️';
        if (str_contains($nombre, 'igualdad') || str_contains($nombre, 'mujeres')) return '⚖️';
        if (str_contains($nombre, 'mística') || str_contains($nombre, 'valores')) return '📜';
        if (str_contains($nombre, 'gobierno') || str_contains($nombre, 'anticorrupción')) return '🏛️';
        if (str_contains($nombre, 'administración')) return '📑';
        if (str_contains($nombre, 'datos') || str_contains($nombre, 'privacidad')) return '🔒';
        return '📚';
    };
@endphp
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-start gap-8">
                <aside class="w-full lg:w-80 flex-shrink-0 space-y-5">
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-4 space-y-2">
                        <div class="px-3 pt-2 pb-1">
                            <span class="text-[10px] font-black tracking-widest text-gray-400">
                                Módulos del Expediente
                            </span>
                        </div>
                        <button id="btn-obligatorios" type="button" onclick="cambiarSeccion('obligatorios')"class="w-full text-left p-3.5 rounded-xl transition-all duration-150 flex items-center justify-between bg-[#691C32] text-white font-bold shadow-md">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">📋</span>
                                <div>
                                    <p class="text-xs leading-snug">Cursos Obligatorios</p>
                                    <p class="text-[10px] opacity-80 font-normal">Periodo 2026–2030</p>
                                </div>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/20 text-white font-extrabold">
                                {{ count($cursosCatalogo) }}
                            </span>
                        </button>

                        <button id="btn-otros" type="button" onclick="cambiarSeccion('otros')" class="w-full text-left p-3.5 rounded-xl transition-all duration-150 flex items-center justify-between bg-transparent text-gray-700 hover:bg-gray-100 font-semibold">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200/60 p-1 flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg viewBox="0 0 64 64" fill="none" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 43H52C53.6569 43 55 44.3431 55 46V47C55 48.6569 53.6569 50 52 50H12C10.3431 50 9 48.6569 9 47V46C9 44.3431 10.3431 43 12 43Z" fill="#D3DCE6" stroke="#111827" stroke-width="2.5" stroke-linejoin="round"/>
                                        <path d="M26 43H38C38 44.5 36.5 46 35 46H29C27.5 46 26 44.5 26 43Z" fill="#B8C4D2" stroke="#111827" stroke-width="2"/>
                                        <rect x="14" y="21" width="36" height="22" rx="3" fill="#5CB874" stroke="#111827" stroke-width="2.5"/>
                                        <path d="M19 35H29" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M19 39H25" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M23 24C23 29 41 29 41 24" fill="#2C2A32" stroke="#111827" stroke-width="2.5"/>
                                        <path d="M32 9L51 17.5L32 26L13 17.5L32 9Z" fill="#403C46" stroke="#111827" stroke-width="2.5" stroke-linejoin="round"/>
                                        <path d="M32 17.5L43 23V27" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                                        <circle cx="43" cy="29" r="2.5" fill="#F7C62B" stroke="#111827" stroke-width="1.5"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs leading-snug">Otros Cursos</p>
                                    <p class="text-[10px] text-gray-400 font-normal">Optativos y Especialidad</p>
                                </div>
                            </div>
                        </button>

                        <button id="btn-semarnat" type="button" onclick="cambiarSeccion('semarnat')" class="w-full text-left p-3.5 rounded-xl transition-all duration-150 flex items-center justify-between bg-transparent text-gray-700 hover:bg-gray-100 font-semibold">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">🏛️</span>
                                <div>
                                    <p class="text-xs leading-snug">Validación SEMARNAT</p>
                                    <p class="text-[10px] text-gray-400 font-normal">Validaciones y Respuestas</p>
                                </div>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold">Validación</span>
                        </button>
                    </div>

                    <div class="p-4 rounded-2xl bg-amber-50/90 border border-amber-300 text-amber-900 shadow-sm space-y-2">
                        <div class="flex items-center gap-2 text-[20px] font-black text-amber-950 tracking-wide">
                            <svg class="w-8 h-8 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Aviso SEMARNAT</span>
                        </div>
                        <p class="text-[18px] leading-relaxed text-amber-900/90">Recuerda mandar todas tus constancias al correo oficial de la secretaría:</p>
                        <a href="mailto:centro.capacitacion@semarnat.gob.mx" class="block text-[15px] font-black text-[#691C32] hover:underline break-all">centro.capacitacion@semarnat.gob.mx</a>
                    </div>
                </aside>

                <main class="w-full lg:flex-1 space-y-6">
                    <div class="relative bg-gradient-to-r from-[#3b0818] via-[#691C32] to-[#26050f] text-white rounded-2xl p-6 sm:p-7 shadow-xl overflow-hidden border border-[#85213e]/30">
                        <div class="absolute -right-16 -top-16 w-64 h-64 bg-[#D4C19C]/10 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative z-10 max-w-2xl">
                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">¡Bienvenido, {{ Auth::user()->nombre ?? Auth::user()->name }} {{ Auth::user()->apellidoP ?? '' }}!</h1>
                            <p class="text-xs sm:text-sm text-rose-100/90 mt-2 leading-relaxed">Selecciona un módulo en el menú lateral para capturar tus constancias o consultar tus avances.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold tracking-wider text-gray-500">Cursos Obligatorios</span>
                                <span class="p-1.5 rounded-lg bg-amber-50 text-amber-700 text-sm">📋</span>
                            </div>
                            <div class="mt-2">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-black text-gray-900">{{ $obligatoriosCompletados }} / {{ count($cursosCatalogo) }}</span>
                                    <span class="text-[11px] font-semibold {{ $obligatoriosCompletados >= count($cursosCatalogo) && count($cursosCatalogo) > 0 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $obligatoriosCompletados >= count($cursosCatalogo) && count($cursosCatalogo) > 0 ? 'Completado' : 'Pendiente' }}</span>
                                </div>
                                <div class="w-full bg-gray-100 h-1.5 rounded-full mt-2 overflow-hidden">
                                    <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ count($cursosCatalogo) > 0 ? ($obligatoriosCompletados / count($cursosCatalogo)) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold tracking-wider text-gray-500">Otros Cursos</span>
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200/60 p-1 flex items-center justify-center shadow-sm">
                                    <svg viewBox="0 0 64 64" fill="none" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 43H52C53.6569 43 55 44.3431 55 46V47C55 48.6569 53.6569 50 52 50H12C10.3431 50 9 48.6569 9 47V46C9 44.3431 10.3431 43 12 43Z" fill="#D3DCE6" stroke="#111827" stroke-width="2.5" stroke-linejoin="round"/>
                                        <path d="M26 43H38C38 44.5 36.5 46 35 46H29C27.5 46 26 44.5 26 43Z" fill="#B8C4D2" stroke="#111827" stroke-width="2"/>
                                        <rect x="14" y="21" width="36" height="22" rx="3" fill="#5CB874" stroke="#111827" stroke-width="2.5"/>
                                        <path d="M19 35H29" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M19 39H25" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M23 24C23 29 41 29 41 24" fill="#2C2A32" stroke="#111827" stroke-width="2.5"/>
                                        <path d="M32 9L51 17.5L32 26L13 17.5L32 9Z" fill="#403C46" stroke="#111827" stroke-width="2.5" stroke-linejoin="round"/>
                                        <path d="M32 17.5L43 23V27" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                                        <circle cx="43" cy="29" r="2.5" fill="#F7C62B" stroke="#111827" stroke-width="1.5"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-black text-gray-900">{{ count($cursosBD) }}</span>
                                    <span class="text-[11px] font-semibold text-emerald-600">Registrados</span>
                                </div>
                                <div class="w-full bg-gray-100 h-1.5 rounded-full mt-2 overflow-hidden">
                                    <div class="bg-emerald-600 h-1.5 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold tracking-wider text-gray-500">Horas Acumuladas</span>
                                <span class="p-1.5 rounded-lg bg-[#691C32]/10 text-[#691C32] text-sm">⏱️</span>
                            </div>
                            <div class="mt-2">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-black text-gray-900">{{ $horasActuales }} hrs / {{ $metaHoras }} hrs</span>
                                    <span class="text-[11px] font-semibold {{ $horasActuales >= $metaHoras ? 'text-emerald-600' : 'text-[#691C32]' }}">{{ $horasActuales >= $metaHoras ? 'Cumplido' : 'Ciclo 2026' }}</span>
                                </div>
                                <div class="w-full bg-gray-100 h-1.5 rounded-full mt-2 overflow-hidden">
                                    <div class="bg-[#691C32] h-1.5 rounded-full transition-all duration-500" style="width: {{ $porcentajeHoras }}%"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="sec-obligatorios" class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                        <div class="p-5 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-black text-gray-900">Cursos Obligatorios 2026–2030</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Para acceder a los cursos ingresa a la siguiente liga:
                                    <a href="https://capacitacion.buengobierno.gob.mx/login/index.php"  target="_blank"  rel="noopener noreferrer" class="font-bold text-[#691C32] hover:underline">https://capacitacion.buengobierno.gob.mx/login</a>
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">Haz clic en la tarjeta del curso para capturar tus horas, calificación y constancia.</p>
                            </div>
                            <span class="text-xs font-extrabold px-3 py-1 rounded-full bg-[#691C32]/10 text-[#691C32]">{{ count($cursosCatalogo) }} Cursos obligatorios</span>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($cursosCatalogo as $curso)
                                @php
                                    $yaEntregado = in_array($curso->nombre, $obligatoriosEntregados ?? []);
                                @endphp
                                <div
                                    @if(!$yaEntregado)
                                        onclick="abrirModalCurso({{ $curso->idCurso }}, '{{ addslashes($curso->nombre) }}', {{ $curso->horas }})"
                                    @endif
                                    class="group bg-white rounded-xl border-2 {{ $yaEntregado ? 'border-green-300 bg-green-50/20' : 'border-gray-200 hover:border-[#691C32] cursor-pointer hover:shadow-md' }} p-4 shadow-sm transition flex flex-col justify-between"
                                >
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="w-8 h-8 rounded-lg {{ $yaEntregado ? 'bg-green-100' : 'bg-[#691C32]/10 group-hover:bg-[#691C32]' }} text-base flex items-center justify-center transition-colors">
                                                <span>{{ $obtenerIcono($curso->nombre) }}</span>
                                            </div>

                                            @if($yaEntregado)
                                                <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                                                    ✓ Acreditado
                                                </span>
                                            @else
                                                <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 group-hover:bg-amber-100 group-hover:text-amber-800 transition">
                                                    Sin entregar
                                                </span>
                                            @endif
                                        </div>

                                        <span class="text-[10px] font-bold tracking-wider {{ $yaEntregado ? 'text-green-700' : 'text-[#691C32]' }}">
                                            ⏱️ {{ $curso->horas }} Horas
                                        </span>

                                        <h4 class="text-xs font-bold text-gray-900 mt-1 leading-snug">
                                            {{ $curso->nombre }}
                                        </h4>
                                    </div>

                                    <div class="mt-4 pt-2.5 border-t border-gray-100 flex items-center justify-between text-[11px] font-bold {{ $yaEntregado ? 'text-green-700' : 'text-[#691C32]' }}">
                                        <span>{{ $yaEntregado ? 'Constancia cargada' : 'Capturar Constancia' }}</span>
                                        @if(!$yaEntregado)
                                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        @else
                                            <span>✓</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-3 text-center py-8 text-gray-400 text-xs">
                                    No hay cursos registrados en el catálogo cat_cursoSemarnat.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div id="sec-otros" class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden hidden">
                        <div class="p-5 border-b border-gray-100 bg-gray-50/60">
                            <h3 class="text-base font-black text-gray-900">Registro de Otros Cursos</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Al adjuntar tu constancia en PDF, el sistema autocompletará los datos.</p>
                        </div>

                        <div class="p-6 space-y-8">
                            <form action="{{ route('cursos.store.otro') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-5 rounded-xl border border-gray-200 space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                                    <div class="sm:col-span-6">
                                        <label class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                            Nombre del curso:
                                        </label>
                                        <input id="input_nombre_curso" type="text" name="nombre_curso" placeholder="Ej. Análisis de Cobertura Vegetal con SIG" required
                                            class="w-full text-xs rounded-lg border-gray-300 focus:ring-2 focus:ring-[#691C32] focus:border-[#691C32] py-2.5 px-3 bg-white" />
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                            Horas de capacitación:
                                        </label>
                                        <input id="input_horas" type="number" min="1" name="horas" placeholder="Ej. 40" required
                                            class="w-full text-xs rounded-lg border-gray-300 focus:ring-2 focus:ring-[#691C32] focus:border-[#691C32] py-2.5 px-3 bg-white" />
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                            Calificación:
                                        </label>
                                        <input id="input_calificacion" type="number" step="0.01" min="0" max="10" name="calificacion" placeholder="Ej. 9.5" required
                                            class="w-full text-xs rounded-lg border-gray-300 focus:ring-2 focus:ring-[#691C32] focus:border-[#691C32] py-2.5 px-3 bg-white" />
                                    </div>

                                    <div class="sm:col-span-9">
                                        <label class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                            Adjuntar Constancia (PDF):
                                        </label>
                                        <input
                                            id="archivo_constancia"
                                            type="file"
                                            name="constancia_otro"
                                            accept=".pdf"
                                            onchange="leerDatosConstancia(this)"
                                            required
                                            class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#691C32]/10 file:text-[#691C32] hover:file:bg-[#691C32]/20 cursor-pointer border border-gray-200 rounded-lg bg-white"
                                        />
                                    </div>

                                    <div class="sm:col-span-3 flex items-end">
                                        <button type="submit"
                                            class="w-full py-2.5 px-4 rounded-lg bg-[#691C32] hover:bg-[#480b1e] text-white font-bold text-xs shadow transition flex items-center justify-center gap-2">
                                            <span>Registrar Curso</span>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="border-t border-gray-100 pt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-extrabold text-gray-900">Historial de Cursos Registrados</h4>
                                </div>

                                <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                                    <table class="w-full text-left text-xs text-gray-600">
                                        <thead class="bg-gray-50 text-[10px] font-black text-gray-500 tracking-wider border-b border-gray-200 uppercase">
                                            <tr>
                                                <th class="py-3 px-4">Nombre del Curso</th>
                                                <th class="py-3 px-4 text-center">Horas</th>
                                                <th class="py-3 px-4 text-center">Calificación</th>
                                                <th class="py-3 px-4 text-center">Constancia</th>
                                                <th class="py-3 px-4 text-center">Estado</th>
                                                <th class="py-3 px-4 text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 bg-white">
                                            @forelse($cursosBD as $curso)
                                                <tr class="hover:bg-gray-50/80 transition">
                                                    <td class="py-3.5 px-4">
                                                        <div class="flex items-center gap-2.5">
                                                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs font-bold">
                                                                🌱
                                                            </span>
                                                            <div>
                                                                <p class="font-bold text-gray-900 text-xs">{{ $curso->nombre }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3.5 px-4 text-center font-bold text-gray-800">{{ $curso->horas }} hrs</td>
                                                    <td class="py-3.5 px-4 text-center">
                                                        <span class="px-2 py-0.5 rounded font-black bg-gray-100 text-gray-800 text-[11px]">
                                                            {{ $curso->calificacion }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3.5 px-4 text-center">
                                                        @if($curso->evidencia)
                                                            <a href="{{ asset('storage/' . $curso->evidencia) }}" target="_blank"
                                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-rose-50 text-[#691C32] hover:bg-rose-100 font-bold text-[11px] border border-rose-200 transition">
                                                                <span>Ver Archivo</span>
                                                            </a>
                                                        @else
                                                            <span class="text-gray-400 text-[10px]">Sin archivo</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3.5 px-4 text-center">
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                            Registrado
                                                        </span>
                                                    </td>
                                                    <td class="py-3.5 px-4 text-center">
                                                        <form id="form-eliminar-{{ $curso->id }}" action="{{ route('cursos.destroy', $curso->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                                type="button"
                                                                onclick="confirmarEliminarCurso({{ $curso->id }}, '{{ addslashes($curso->nombre) }}')"
                                                                class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 transition cursor-pointer"
                                                                title="Eliminar curso"
                                                            >
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-6 text-gray-400 text-xs">
                                                        No tienes otros cursos registrados todavía.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="sec-semarnat" class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden hidden">
                        <div class="p-5 border-b border-gray-100 bg-gray-50/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h3 class="text-base font-black text-gray-900">Validación de cursos por SEMARNAT</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Valida tus cursos registrados cargando la respuesta o correo oficial de confirmación de SEMARNAT.</p>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-blue-100 text-blue-800 self-start sm:self-auto">
                                {{ count($cursosBD) }} cursos registrados
                            </span>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                                <table class="w-full text-left text-xs text-gray-600">
                                    <thead class="bg-blue-50/60 text-[10px] font-black text-blue-950 uppercase tracking-wider border-b border-blue-100">
                                        <tr>
                                            <th class="py-3 px-4">Nombre del Curso</th>
                                            <th class="py-3 px-4 text-center">Horas</th>
                                            <th class="py-3 px-4 text-center">Calificación</th>
                                            <th class="py-3 px-4 text-center">Constancia Original</th>
                                            <th class="py-3 px-4 text-center">Estado SEMARNAT</th>
                                            <th class="py-3 px-4 text-center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                       @forelse($todosLosCursos as $curso)
                                            <tr class="hover:bg-blue-50/30 transition">
                                                <td class="py-3.5 px-4 font-bold text-gray-900">
                                                    <div class="flex items-center gap-2">
                                                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs font-bold">
                                                            🌱
                                                        </span>
                                                        <span>{{ $curso->nombre }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-3.5 px-4 text-center font-bold text-gray-800">
                                                    {{ $curso->horas }} hrs
                                                </td>
                                                <td class="py-3.5 px-4 text-center font-bold text-gray-800">
                                                    <span class="px-2 py-0.5 rounded font-black bg-gray-100 text-gray-800 text-[11px]">
                                                        {{ $curso->calificacion }}
                                                    </span>
                                                </td>
                                                <td class="py-3.5 px-4 text-center">
                                                    @if($curso->evidencia)
                                                        <a href="{{ asset('storage/' . $curso->evidencia) }}" target="_blank"
                                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-[11px] transition">
                                                            <span>Ver Archivo</span>
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400 text-[11px] italic">Sin constancia</span>
                                                    @endif
                                                </td>
                                                <td class="py-3.5 px-4 text-center">
                                                    @if($curso->validadoSemarnat == 1)
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                            Validado
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                            Pendiente
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3.5 px-4 text-center">
                                                    @if($curso->validadoSemarnat == 1)
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-400 font-bold text-[11px] cursor-not-allowed select-none border border-gray-200">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                            </svg>
                                                            <span>Validado</span>
                                                        </span>
                                                    @else
                                                        <button type="button" onclick="abrirModalSemarnat('{{ $curso->id }}', '{{ addslashes($curso->nombre) }}')" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-800 hover:bg-blue-900 text-white font-bold text-[11px] shadow-sm transition hover:shadow cursor-pointer">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span>Validar</span>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-8 text-gray-400 text-xs">
                                                    No tienes cursos registrados en la base de datos aún. Registra cursos primero en la pestaña "Otros Cursos".
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <div id="modal_curso" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-[#46081c] to-[#691C32] text-white p-5 flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-bold tracking-widest text-[#D4C19C]">Registro de Avance</span>
                    <h3 id="modal_curso_titulo" class="text-base font-extrabold mt-0.5 leading-snug"></h3>
                </div>
                <button type="button" onclick="cerrarModalCurso()" class="text-rose-200 hover:text-white p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('cursos.store.obligatorio') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="modal_curso_id" name="curso_id" value="">
                <input type="hidden" id="modal_curso_nombre" name="nombre_curso" value="">
                <input type="hidden" id="modal_curso_horas" name="horas" value="">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-700 mb-1">Horas de capacitación</label>
                    <input type="number" id="modal_curso_horas_vista" disabled class="w-full text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-600 font-bold cursor-not-allowed select-none py-2 px-3"/>
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-700 mb-1">Calificación obtenida</label>
                    <input type="number" step="0.01" min="0" max="10" name="calificacion" placeholder="Ej. 10" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-[#691C32] focus:border-[#691C32] py-2 px-3" oninput="if(parseFloat(this.value) > 10) this.value = 10; if(parseFloat(this.value) < 0) this.value = 0;" />
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-700 mb-1">Adjuntar Constancia (PDF)</label>
                    <input type="file" name="constancia" accept=".pdf" required class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#691C32]/10 file:text-[#691C32] hover:file:bg-[#691C32]/20 cursor-pointer border border-gray-200 rounded-lg bg-gray-50" />
                </div>
                <div class="p-3.5 rounded-xl border border-amber-200 bg-amber-50/70 space-y-1">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" name="enviar_correo" id="modal_enviar_correo" value="1" class="rounded border-gray-300 text-[#691C32] focus:ring-[#691C32] w-4 h-4 cursor-pointer"/>
                        <span class="text-xs font-bold text-gray-800">Enviar correo a Centro de Capacitación SEMARNAT</span>
                    </label>
                    <p class="text-[11px] text-amber-900 leading-relaxed pl-6.5">
                        <strong class="font-bold">Nota:</strong> Si ya enviaste las constancias a SEMARNAT, no hace falta que se vuelvan a enviar.
                    </p>
                </div>
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="cerrarModalCurso()" class="px-4 py-2 rounded-lg border border-gray-300 text-xs font-bold text-gray-600 hover:bg-gray-100 transition">Cancelar</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-[#691C32] hover:bg-[#480b1e] text-white text-xs font-bold shadow transition">Guardar Acreditación</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal_semarnat" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-blue-900 to-blue-700 text-white p-5 flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-bold tracking-widest text-blue-200 uppercase">Validación Institucional</span>
                    <h3 id="modal_semarnat_titulo" class="text-base font-extrabold mt-0.5 leading-snug">Validar Curso con SEMARNAT</h3>
                </div>
                <button type="button" onclick="cerrarModalSemarnat()" class="text-blue-200 hover:text-white p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('cursos.validar.semarnat') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="modal_semarnat_curso_id" name="curso_id" value="">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-700 mb-1">Curso a validar:</label>
                    <input type="text" id="modal_semarnat_curso_nombre" readonly class="w-full text-xs font-semibold bg-gray-50 border-gray-200 rounded-lg text-gray-700 py-2.5 px-3 select-none" />
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-700 mb-1">Cargar PDF de respuesta (Correo o Acuse SEMARNAT):</label>
                    <input type="file" name="comprobante_semarnat" accept=".pdf,.png,.jpg,.jpeg" required class="w-full text-xs text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-700/10 file:text-blue-800 hover:file:bg-blue-700/20 cursor-pointer border border-gray-200 rounded-lg bg-gray-50"/>
                    <p class="text-[10px] text-gray-400 mt-1">Al subir este archivo el curso se actualizará automáticamente como <strong>Validado (1)</strong>.</p>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="cerrarModalSemarnat()" class="px-4 py-2 rounded-lg border border-gray-300 text-xs font-bold text-gray-600 hover:bg-gray-100 transition">Cancelar</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-blue-800 hover:bg-blue-900 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span>Subir y Validar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function cambiarSeccion(seccion) {
            document.getElementById('sec-obligatorios').classList.add('hidden');
            document.getElementById('sec-otros').classList.add('hidden');
            document.getElementById('sec-semarnat').classList.add('hidden');
            const botones = ['btn-obligatorios', 'btn-otros', 'btn-semarnat'];
            botones.forEach(btnId => {
                const btn = document.getElementById(btnId);
                btn.className = "w-full text-left p-3.5 rounded-xl transition-all duration-150 flex items-center justify-between bg-transparent text-gray-700 hover:bg-gray-100 font-semibold";
            });
            document.getElementById('sec-' + seccion).classList.remove('hidden');
            document.getElementById('btn-' + seccion).className = "w-full text-left p-3.5 rounded-xl transition-all duration-150 flex items-center justify-between bg-[#691C32] text-white font-bold shadow-md";
        }

        function abrirModalCurso(id, titulo, horas) {
            document.getElementById('modal_curso_id').value = id;
            document.getElementById('modal_curso_titulo').innerText = titulo;
            document.getElementById('modal_curso_nombre').value = titulo;
            document.getElementById('modal_curso_horas_vista').value = horas;
            document.getElementById('modal_curso_horas').value = horas;
            document.getElementById('modal_curso').classList.remove('hidden');
        }

        function cerrarModalCurso() {
            document.getElementById('modal_curso').classList.add('hidden');
        }

        function abrirModalSemarnat(id, titulo) {
            document.getElementById('modal_semarnat_curso_id').value = id;
            document.getElementById('modal_semarnat_curso_nombre').value = titulo;
            document.getElementById('modal_semarnat_titulo').innerText = 'Validar: ' + titulo;
            document.getElementById('modal_semarnat').classList.remove('hidden');
        }

        function cerrarModalSemarnat() {
            document.getElementById('modal_semarnat').classList.add('hidden');
        }

        function confirmarEliminarCurso(id, nombre) {
            Swal.fire({
                title: '¿Eliminar curso?',
                html: `¿Estás seguro de que deseas eliminar el curso <strong>"${nombre}"</strong>?<br><span class="text-xs text-red-500">Esta acción no se puede deshacer.</span>`,
                icon: 'warning',
                iconColor: '#dc2626',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar curso',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-gray-100',
                    confirmButton: 'rounded-xl text-xs font-bold px-4 py-2.5',
                    cancelButton: 'rounded-xl text-xs font-medium px-4 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-eliminar-' + id).submit();
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                cerrarModalCurso();
                cerrarModalSemarnat();
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                html: {!! json_encode(session('success')) !!},
                confirmButtonColor: '#691C32'
            }).then(() => {
                cambiarSeccion('{{ session("seccion", "otros") }}');
            });
        @endif
    </script>
    @php
        $mostrarAlertaLogin = false;
        if ($obligatoriosCompletados < count($cursosCatalogo) && !session('alerta_obligatorios_mostrada')) {
            $mostrarAlertaLogin = true;
            session(['alerta_obligatorios_mostrada' => true]);
        }
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if($mostrarAlertaLogin)
                Swal.fire({
                    title: '¡Cursos obligatorios!',
                    html: `
                        <div class="text-left space-y-3">
                            <p class="text-sm text-gray-600">
                                Estimado(a) trabajador(a), actualmente cuentas con <strong class="text-amber-700">{{$obligatoriosCompletados}} de {{ count($cursosCatalogo) }} cursos obligatorios</strong> acreditados para el ciclo normativo.
                            </p>
                            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 leading-relaxed">
                                ⚠️ <strong>Recuerda:</strong> Es indispensable cursar y cargar tus constancias oficiales para dar cumplimiento a los lineamientos institucionales de <strong>CONABIO</strong>.
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    iconColor: '#691C32',
                    confirmButtonText: 'Ir a mis Cursos Obligatorios →',
                    confirmButtonColor: '#691C32',
                    showCancelButton: true,
                    cancelButtonText: 'Entendido, más tarde',
                    cancelButtonColor: '#6b7280',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-gray-100',
                        confirmButton: 'rounded-xl text-xs font-bold px-4 py-2.5',
                        cancelButton: 'rounded-xl text-xs font-medium px-4 py-2.5'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        cambiarSeccion('obligatorios');
                    }
                });
            @endif
        });
    </script>
</x-app-layout>
