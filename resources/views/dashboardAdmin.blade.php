<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @php
        $cursosCatalogo = $cursosCatalogo ?? \Illuminate\Support\Facades\DB::table('cat_cursosemarnat')->get();
        $totalHorasCatalogo = $cursosCatalogo->sum('horas');
        $pctMeta = $totalEmpleados > 0 ? round(($empleadosMetaCumplida / $totalEmpleados) * 100) : 0;
        $pctPendiente = 100 - $pctMeta;
        $pctConCursos = $totalEmpleados > 0 ? round(($empleadosConCursos / $totalEmpleados) * 100) : 0;
        $pctSinCursos = 100 - $pctConCursos;

        $obtenerIcono = function($nombre) {
            $nombre = mb_strtolower($nombre, 'UTF-8');
            if (str_contains($nombre, 'ciberseguridad')) return '🛡️';
            if (str_contains($nombre, 'igualdad') || str_contains($nombre, 'mujeres')) return '⚖️';
            if (str_contains($nombre, 'mística') || str_contains($nombre, 'ética') || str_contains($nombre, 'valores')) return '📜';
            if (str_contains($nombre, 'gobierno') || str_contains($nombre, 'anticorrupción')) return '🏛️';
            if (str_contains($nombre, 'administración') || str_contains($nombre, 'pública')) return '📑';
            if (str_contains($nombre, 'datos') || str_contains($nombre, 'privacidad')) return '🔒';
            return '📚';
        };
    @endphp

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-start gap-6 w-full">
                <aside class="w-full lg:w-80 flex-shrink-0 space-y-4" style="min-width: 280px; max-width: 320px;">
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-4 space-y-2">
                        <div class="px-3 pt-2 pb-1"><span class="text-[10px] font-black tracking-widest text-gray-400 uppercase">Panel Administrador</span></div>
                        <button id="btn-estadisticas" type="button" onclick="cambiarModuloAdmin('estadisticas')" class="w-full text-left p-3.5 rounded-xl transition-all duration-150 flex items-center justify-between bg-[#691C32] text-white font-bold shadow-md cursor-pointer">
                            <div class="flex items-center gap-3"><span class="text-lg">📊</span><div><p class="text-xs leading-snug">Estadísticas y Reportes</p><p class="text-[10px] opacity-80 font-normal">Gráficas y descargas</p></div></div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/20 text-white font-bold">Métricas</span>
                        </button>
                        <button id="btn-empleados" type="button" onclick="cambiarModuloAdmin('empleados')" class="w-full text-left p-3.5 rounded-xl transition-all duration-150 flex items-center justify-between bg-transparent text-gray-700 hover:bg-gray-100 font-semibold cursor-pointer">
                            <div class="flex items-center gap-3"><span class="text-lg">👥</span><div><p class="text-xs leading-snug">Empleados y Cursos</p><p class="text-[10px] text-gray-400 font-normal">Expediente general</p></div></div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-extrabold">{{ $totalEmpleados }}</span>
                        </button>
                        <button id="btn-cursos" type="button" onclick="cambiarModuloAdmin('cursos')" class="w-full text-left p-3.5 rounded-xl transition-all duration-150 flex items-center justify-between bg-transparent text-gray-700 hover:bg-gray-100 font-semibold cursor-pointer">
                            <div class="flex items-center gap-3"><span class="text-lg">➕</span><div><p class="text-xs leading-snug">Agregar Curso Obligatorio</p><p class="text-[10px] text-gray-400 font-normal">Catálogo SEMARNAT</p></div></div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 font-bold border border-amber-200">{{ count($cursosCatalogo) }}</span>
                        </button>
                    </div>

                    <div class="p-4 rounded-2xl bg-white border border-gray-200/80 shadow-sm space-y-3">
                        <span class="text-[11px] font-black text-gray-500 tracking-wider block uppercase">Avance Global</span>
                        <div class="flex justify-between items-center text-xs"><span class="text-gray-600">Meta 40 hrs:</span><span class="font-extrabold text-emerald-600">{{ $empleadosMetaCumplida }} / {{ $totalEmpleados }}</span></div>
                        <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ $totalEmpleados > 0 ? ($empleadosMetaCumplida / $totalEmpleados) * 100 : 0 }}%"></div>
                        </div>
                        <div class="flex justify-between items-center text-xs pt-2 border-t border-gray-100"><span class="text-gray-600">Sin cursos aún:</span><span class="font-extrabold text-rose-600">{{ $empleadosSinCursos }} empleado(s)</span></div>
                    </div>
                </aside>

                <main class="flex-1 w-full min-w-0 space-y-6">
                    <div id="sec-empleados" class="hidden space-y-4">
                        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="relative flex-1 max-w-md">
                                <input type="text" id="input_buscador" onkeyup="filtrarTabla()" placeholder="Buscar por No. Empleado, nombre o curso..." class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#691C32] focus:border-[#691C32]"/>
                                <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 font-semibold mr-1">Total: <strong class="text-gray-900">{{ count($empleados) }}</strong> empleado(s)</span>
                                <button type="button" onclick="abrirModalZipCurso()" class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#691C32] hover:bg-[#4a1223] text-white rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Bajar constancias por curso</span>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
                            <button type="button" onclick="filtrarPorEstado('todos', this)" class="btn-filtro-estado px-3 py-1.5 rounded-xl font-bold bg-[#691C32] text-white shadow-xs transition cursor-pointer">Todos ({{ count($empleados) }})</button>
                            <button type="button" onclick="filtrarPorEstado('cumplida', this)" class="btn-filtro-estado px-3 py-1.5 rounded-xl font-semibold bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition cursor-pointer flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Meta Cumplida ≥ 40h ({{ $empleadosMetaCumplida }})</button>
                            <button type="button" onclick="filtrarPorEstado('proceso', this)" class="btn-filtro-estado px-3 py-1.5 rounded-xl font-semibold bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition cursor-pointer flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span>En Proceso < 40h ({{ max(0, $totalEmpleados - $empleadosMetaCumplida) }})</button>
                            <button type="button" onclick="filtrarPorEstado('sin_iniciar', this)" class="btn-filtro-estado px-3 py-1.5 rounded-xl font-semibold bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition cursor-pointer flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span>Sin Iniciar ({{ $empleadosSinCursos }})</button>
                        </div>

                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs text-gray-600" id="tabla_empleados">
                                    <thead class="bg-[#691C32] text-white text-[10px] font-black tracking-wider uppercase">
                                        <tr>
                                            <th class="py-3.5 px-4 text-center w-14">No.</th>
                                            <th class="py-3.5 px-4">Nombre Completo</th>
                                            <th class="py-3.5 px-4 text-center w-28 min-w-[7rem] whitespace-nowrap">Cursos</th>
                                            <th class="py-3.5 px-4 text-center w-44">Avance Horas</th>
                                            <th class="py-3.5 px-3 text-center w-28">Expediente</th>
                                            <th class="py-3.5 px-3 text-center w-28">Recordatorio</th>
                                            <th class="py-3.5 px-3 text-center w-24">Descargar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @forelse($empleados as $emp)
                                            @php
                                                $totalHoras = $emp->cursos->sum('horas');
                                                $tieneCursos = $emp->cursos->count() > 0;
                                                $idEmp = $emp->idEmpleado ?? $emp->id;
                                                $porcentajeEmp = min(100, round(($totalHoras / 40) * 100));

                                                if (!$tieneCursos) {
                                                    $estadoEmp = 'sin_iniciar';
                                                } elseif ($totalHoras >= 40) {
                                                    $estadoEmp = 'cumplida';
                                                } else {
                                                    $estadoEmp = 'proceso';
                                                }
                                            @endphp
                                            <tr class="hover:bg-gray-50/80 transition fila-empleado" data-estado="{{ $estadoEmp }}">
                                                <td class="py-3.5 px-4 text-center font-bold text-gray-900">{{ $idEmp }}</td>
                                                <td class="py-3.5 px-4 font-extrabold text-gray-900">
                                                    <div>{{ $emp->nombre }} {{ $emp->apellidoP }} {{ $emp->apellidoM }}</div>
                                                    <div class="text-[10px] font-normal text-gray-400">{{ $emp->correo }}</div>
                                                </td>
                                                <td class="py-3.5 px-4 text-center w-28 min-w-[7rem]">
                                                    @if($tieneCursos)
                                                        <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap px-3 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                                            <span class="font-black">{{ $emp->cursos->count() }}</span>
                                                            <span>{{ $emp->cursos->count() === 1 ? 'curso' : 'cursos' }}</span>
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap px-3 py-1 rounded-full text-[11px] font-semibold bg-gray-50 text-gray-500 border border-gray-200">
                                                            <span class="font-black">0</span>
                                                            <span>cursos</span>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3.5 px-4 text-center">
                                                    <div class="flex items-center justify-between text-[11px] font-bold mb-1">
                                                        <span class="{{ $totalHoras >= 40 ? 'text-emerald-700' : 'text-gray-700' }}">{{ $totalHoras }} / 40 hrs</span>
                                                        <span class="text-[10px] {{ $totalHoras >= 40 ? 'text-emerald-700' : 'text-amber-600' }}">{{ $porcentajeEmp }}%</span>
                                                    </div>
                                                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                                        <div class="h-2 rounded-full transition-all duration-500 {{ $totalHoras >= 40 ? 'bg-emerald-600' : 'bg-amber-500' }}" style="width: {{ $porcentajeEmp }}%"></div>
                                                    </div>
                                                </td>
                                                <td class="py-3.5 px-3 text-center">
                                                    @if($tieneCursos)
                                                        <button
                                                            type="button"
                                                            onclick="toggleCursos('cursos-emp-{{ $idEmp }}', this)"
                                                            class="px-2.5 py-1 bg-gray-100 hover:bg-[#691C32] hover:text-white rounded-lg text-[10px] font-bold text-gray-700 transition cursor-pointer whitespace-nowrap"
                                                        >
                                                            Ver Cursos ▾
                                                        </button>
                                                    @else
                                                        <span class="text-gray-300 font-bold select-none">—</span>
                                                    @endif
                                                </td>
                                                <td class="py-3.5 px-3 text-center">
                                                    @if($totalHoras < 40)
                                                        <button
                                                            type="button"
                                                            onclick="confirmarRecordatorio({{ $idEmp }}, '{{ addslashes($emp->nombre) }}', '{{ $emp->correo }}')"
                                                            class="px-2.5 py-1 bg-amber-50 hover:bg-amber-500 hover:text-white text-amber-800 rounded-lg text-[10px] font-bold border border-amber-200 transition inline-flex items-center gap-1 cursor-pointer whitespace-nowrap shadow-2xs"
                                                            title="Enviar correo de recordatorio de cursos faltantes"
                                                        >
                                                            <span>📧 Recordar</span>
                                                        </button>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 select-none">
                                                            ✓ Al día
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3.5 px-3 text-center">
                                                    @if($tieneCursos)
                                                        <a
                                                            href="{{ route('admin.descargar.zip.empleado', $idEmp) }}"
                                                            class="px-2.5 py-1 bg-emerald-50 text-emerald-800 hover:bg-emerald-700 hover:text-white rounded-lg text-[10px] font-bold border border-emerald-200 transition inline-flex items-center gap-1 cursor-pointer whitespace-nowrap shadow-2xs"
                                                            title="Descargar todas las constancias de {{ $emp->nombre }} en ZIP"
                                                        >
                                                            <span>📦 ZIP</span>
                                                        </a>
                                                    @else
                                                        <span class="text-gray-300 font-bold select-none">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($tieneCursos)
                                                <tr id="cursos-emp-{{ $idEmp }}" class="hidden bg-gray-50/80">
                                                    <td colspan="7" class="p-4">
                                                        <div class="rounded-xl border border-gray-200 bg-white p-3 space-y-2">
                                                            <div class="flex items-center justify-between pb-1 border-b border-gray-100">
                                                                <span class="text-[11px] font-extrabold text-gray-700 block">
                                                                    Cursos registrados por {{ $emp->nombre }}:
                                                                </span>
                                                                <a
                                                                    href="{{ route('admin.descargar.zip.empleado', $idEmp) }}"
                                                                    class="text-[10px] text-emerald-700 hover:underline font-bold inline-flex items-center gap-1"
                                                                >
                                                                    <span>📦 Descargar todas las constancias (.zip)</span>
                                                                </a>
                                                            </div>
                                                            <div class="overflow-x-auto">
                                                            <table class="w-full min-w-[1020px] text-left text-xs">
                                                                <thead class="text-[11px] font-bold text-gray-500 border-b border-gray-100">
                                                                    <tr>
                                                                        <th class="px-3 py-2">Nombre del Curso</th>
                                                                        <th class="px-3 py-2 text-center whitespace-nowrap">Tipo</th>
                                                                        <th class="px-3 py-2 text-center whitespace-nowrap">Horas</th>
                                                                        <th class="px-3 py-2 text-center whitespace-nowrap">Periodo</th>
                                                                        <th class="px-3 py-2 text-center whitespace-nowrap">Calificación</th>
                                                                        <th class="px-3 py-2 text-center whitespace-nowrap">Validación SEMARNAT</th>
                                                                        <th class="px-3 py-2 text-center whitespace-nowrap">Respuesta SEMARNAT</th>
                                                                        <th class="px-3 py-2 text-center whitespace-nowrap">Constancia</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-gray-100">
                                                                    @foreach($emp->cursos as $c)
                                                                        <tr>
                                                                            <td class="px-3 py-3 text-gray-800 font-bold min-w-[260px]">{{ $c->nombre }}</td>
                                                                            <td class="px-3 py-3 text-center">
                                                                                <span class="text-[9px] px-2 py-0.5 rounded-full font-extrabold {{ $c->obligatorio ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                                                                                    {{ $c->obligatorio ? 'Obligatorio' : 'Otros' }}
                                                                                </span>
                                                                            </td>
                                                                            <td class="px-3 py-3 text-center font-bold text-gray-700 whitespace-nowrap">{{ $c->horas }} hrs</td>
                                                                            <td class="px-3 py-3 text-center font-bold text-gray-700 whitespace-nowrap">{{ $c->periodo ?? '—' }}</td>
                                                                            <td class="px-3 py-3 text-center font-bold text-gray-800 whitespace-nowrap">{{ $c->calificacion ?? 'S/C' }}</td>

                                                                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                                                                @if(isset($c->validadoSemarnat) && $c->validadoSemarnat == 1)
                                                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                                                        <span>✓</span> Validado
                                                                                    </span>
                                                                                @else
                                                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                                                                        <span>⏳</span> Pendiente
                                                                                    </span>
                                                                                @endif
                                                                            </td>

                                                                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                                                                @if($c->validacion)
                                                                                    <a href="{{ route('cursos.ver.validacion', $c->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded border border-blue-200 bg-blue-50 text-[10px] font-bold text-blue-800 hover:bg-blue-100 transition">📄 Ver respuesta</a>
                                                                                @else
                                                                                    <span class="text-[10px] text-gray-400">Sin archivo</span>
                                                                                @endif
                                                                            </td>

                                                                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                                                                @if($c->evidencia)
                                                                                    <button
                                                                                        type="button"
                                                                                        onclick="abrirModalPreviewPdf('{{ route('cursos.ver.evidencia', $c->id) }}', '{{ addslashes($c->nombre) }}')"
                                                                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-[#691C32] hover:bg-rose-100 font-bold text-[10px] rounded border border-rose-200 transition cursor-pointer"
                                                                                        title="Ver constancia en vista previa"
                                                                                    >
                                                                                        📄 Ver Archivo
                                                                                    </button>
                                                                                @else
                                                                                    <span class="text-gray-400 text-[10px]">Sin archivo</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-8 text-gray-400">No hay empleados registrados en el sistema.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="sec-estadisticas" class="space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-3 border-b border-gray-200 gap-3">
                            <div><h3 class="text-lg font-black text-gray-900 tracking-tight">Métricas de Cumplimiento Institucional</h3><p class="text-xs text-gray-500 mt-0.5">Seguimiento en tiempo real y descarga de reportes oficiales.</p></div>
                            <a href="{{ route('admin.reportes.excel', ['tipo' => 'todos']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-md transition self-start sm:self-auto hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Descargar reporte general</span>
                            </a>
                        </div>

                        <div class="bg-gradient-to-r from-gray-900 via-slate-800 to-gray-900 text-white rounded-2xl p-5 shadow-sm space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-1">
                                <a href="{{ route('admin.reportes.excel', ['tipo' => 'meta_cumplida']) }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-xl transition border border-white/10 flex items-center justify-between group">
                                    <div><p class="text-xs font-bold text-emerald-300">Meta 40 hrs Cumplida</p><p class="text-[10px] text-gray-300">{{ $empleadosMetaCumplida }} empleado(s)</p></div>
                                    <span class="text-lg group-hover:translate-y-0.5 transition-transform">📥</span>
                                </a>
                                <a href="{{ route('admin.reportes.excel', ['tipo' => 'pendientes_horas']) }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-xl transition border border-white/10 flex items-center justify-between group">
                                    <div><p class="text-xs font-bold text-amber-300">Pendientes (< 40 hrs)</p><p class="text-[10px] text-gray-300">{{ max(0, $totalEmpleados - $empleadosMetaCumplida) }} empleado(s)</p></div>
                                    <span class="text-lg group-hover:translate-y-0.5 transition-transform">📥</span>
                                </a>
                                <a href="{{ route('admin.reportes.excel', ['tipo' => 'obligatorios_listos']) }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-xl transition border border-white/10 flex items-center justify-between group">
                                    <div><p class="text-xs font-bold text-blue-300">Obligatorios al 100%</p><p class="text-[10px] text-gray-300">{{ $empleadosObligatoriosCompletos }} empleado(s)</p></div>
                                    <span class="text-lg group-hover:translate-y-0.5 transition-transform">📥</span>
                                </a>
                                <a href="{{ route('admin.reportes.excel', ['tipo' => 'sin_iniciar']) }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-xl transition border border-white/10 flex items-center justify-between group">
                                    <div><p class="text-xs font-bold text-rose-300">Sin Ningún Curso</p><p class="text-[10px] text-gray-300">{{ $empleadosSinCursos }} empleado(s)</p></div>
                                    <span class="text-lg group-hover:translate-y-0.5 transition-transform">📥</span>
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div><span class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Empleados</span><p class="text-3xl font-black text-gray-900 mt-1">{{ $totalEmpleados }}</p></div>
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-lg">👥</div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500 font-medium"><span>Empleados activos</span><span class="font-bold text-gray-700">100%</span></div>
                            </div>
                            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div><span class="text-[11px] font-bold tracking-wider uppercase text-emerald-600">Meta 40 Horas</span><p class="text-3xl font-black text-emerald-700 mt-1">{{ $empleadosMetaCumplida }}</p></div>
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center text-lg">⏱️</div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-medium"><span class="text-gray-500">Han acreditado ≥ 40 hrs</span><span class="font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">{{ $pctMeta }}%</span></div>
                            </div>
                            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div><span class="text-[11px] font-bold tracking-wider uppercase text-amber-600">Obligatorios listos</span><p class="text-3xl font-black text-amber-700 mt-1">{{ $empleadosObligatoriosCompletos }}</p></div>
                                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 border border-amber-100 flex items-center justify-center text-lg">📜</div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-medium"><span class="text-gray-500">{{ $totalCatalogoObligatorios }} cursos concluidos</span><span class="font-extrabold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">{{ $totalEmpleados > 0 ? round(($empleadosObligatoriosCompletos / $totalEmpleados) * 100) : 0 }}%</span></div>
                            </div>
                            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div><span class="text-[11px] font-bold tracking-wider uppercase text-rose-600">Sin iniciar</span><p class="text-3xl font-black text-rose-700 mt-1">{{ $empleadosSinCursos }}</p></div>
                                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-700 border border-rose-100 flex items-center justify-center text-lg">⚠️</div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-medium"><span class="text-gray-500">0 constancias subidas</span><span class="font-extrabold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md">{{ $pctSinCursos }}%</span></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm space-y-4 hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div><h4 class="text-xs font-black text-gray-900 tracking-wider uppercase">Cumplimiento de Meta Anual</h4><p class="text-[11px] text-gray-400 mt-0.5">Avance hacia las 40 horas normativas</p></div>
                                    <span class="p-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-bold">⏱️ 40 hrs</span>
                                </div>
                                <div class="relative h-64 w-full flex items-center justify-center"><canvas id="graficaHoras"></canvas></div>
                            </div>
                            <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm space-y-4 hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div><h4 class="text-xs font-black text-gray-900 tracking-wider uppercase">Participación de los empleados</h4><p class="text-[11px] text-gray-400 mt-0.5">Actividad general en la plataforma</p></div>
                                    <span class="p-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold">📊 Actividad</span>
                                </div>
                                <div class="relative h-64 w-full flex items-center justify-center"><canvas id="graficaParticipacion"></canvas></div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm space-y-4 hover:shadow-md transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                                <div><h4 class="text-xs font-black text-gray-900 tracking-wider uppercase">Acreditación por Curso Obligatorio</h4><p class="text-[11px] text-gray-400 mt-0.5">Número de empleados que ya entregaron constancia por curso del catálogo</p></div>
                                <span class="self-start sm:self-auto px-3 py-1 bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold rounded-lg">{{ $totalEmpleados }} empleados</span>
                            </div>
                            <div class="relative w-full" style="height: {{ max(300, count($estadisticasCursos) * 45) }}px;"><canvas id="graficaCursos"></canvas></div>
                        </div>
                    </div>

                    <div id="sec-cursos" class="hidden space-y-6">
                        <div class="bg-white rounded-2xl border border-gray-200/90 shadow-sm p-6 space-y-4">
                            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                                <div class="w-9 h-9 rounded-xl bg-[#691C32]/10 text-[#691C32] flex items-center justify-center font-black text-base shadow-sm">➕</div>
                                <div><h4 class="text-sm font-black text-gray-900">Dar de alta un nuevo curso obligatorio</h4><p class="text-[11px] text-gray-400">Ingresa los datos exactos del programa de capacitación oficial.</p></div>
                            </div>
                            <form action="{{ route('admin.cursos.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-4 pt-1">
                                @csrf
                                <div class="sm:col-span-8">
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Nombre oficial del curso:</label>
                                    <input type="text" name="nombre" placeholder="Ej. Formación en Igualdad Sustantiva y Derechos Humanos" required class="w-full text-xs pl-3 pr-3 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#691C32] focus:border-[#691C32] bg-gray-50/50 focus:bg-white transition"/>
                                </div>
                                <div class="sm:col-span-4">
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Horas de valor curricular:</label>
                                    <div class="flex gap-2">
                                        <input type="number" name="horas" min="1" max="100" placeholder="Ej. 10" required class="w-full text-xs pl-3 pr-2 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#691C32] focus:border-[#691C32] bg-gray-50/50 focus:bg-white transition"/>
                                        <button type="submit" class="px-5 py-2.5 bg-[#691C32] hover:bg-[#501325] text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-1.5 flex-shrink-0 cursor-pointer">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden space-y-0">
                            <div class="p-4 sm:p-5 border-b border-gray-100 bg-gray-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div><h4 class="text-xs font-black text-gray-800 uppercase tracking-wider">Cursos vigentes en el catálogo</h4><p class="text-[11px] text-gray-400 mt-0.5">Normativa aplicable para la acreditación anual.</p></div>
                                <div class="relative max-w-xs w-full">
                                    <input type="text" id="input_buscar_curso" onkeyup="filtrarCursosCatalogo()" placeholder="Buscar en el catálogo..." class="w-full text-[11px] pl-8 pr-3 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#691C32] focus:border-[#691C32] bg-white"/>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs text-gray-600" id="tabla_catalogo">
                                    <thead class="bg-gray-100/70 text-[10px] font-black text-gray-500 uppercase tracking-wider border-b border-gray-200/80">
                                        <tr>
                                            <th class="py-3.5 px-4 text-center w-12">#</th>
                                            <th class="py-3.5 px-4">Nombre del Curso</th>
                                            <th class="py-3.5 px-4 text-center">Duración</th>
                                            <th class="py-3.5 px-4 text-center">Tipo</th>
                                            <th class="py-3.5 px-4 text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @forelse($cursosCatalogo as $index => $cCat)
                                            @php $idCursoActual = $cCat->idCurso ?? ($cCat->id ?? ($index + 1)); @endphp
                                            <tr class="hover:bg-rose-50/30 transition fila-curso-catalogo">
                                                <td class="py-4 px-4 text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                                                <td class="py-4 px-4 font-bold text-gray-900"><span class="nombre-curso-item leading-snug">{{ $cCat->nombre }}</span></td>
                                                <td class="py-4 px-4 text-center"><span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-[#691C32]/10 text-[#691C32]">⏱️ {{ $cCat->horas }} hrs</span></td>
                                                <td class="py-4 px-4 text-center"><span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200">Obligatorio</span></td>
                                                <td class="py-4 px-4 text-center">
                                                    <div class="flex items-center justify-center gap-1.5">
                                                        <button type="button" onclick="abrirModalEditarCurso({{ $idCursoActual }}, '{{ addslashes($cCat->nombre) }}', {{ $cCat->horas }})" class="p-1.5 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-blue-50 transition cursor-pointer" title="Editar">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        </button>
                                                        <form id="form-del-curso-{{ $idCursoActual }}" action="{{ route('admin.cursos.destroy', $idCursoActual) }}" method="POST" class="inline">
                                                            @csrf @method('DELETE')
                                                            <button type="button" onclick="confirmarEliminarCursoCatalogo({{ $idCursoActual }}, '{{ addslashes($cCat->nombre) }}')" class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition cursor-pointer" title="Eliminar">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center py-8 text-gray-400 text-xs">No hay cursos registrados en el catálogo aún.</td></tr>
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

    <div id="modal_editar_curso" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-[#46081c] to-[#691C32] text-white p-5 flex items-center justify-between">
                <div class="flex items-center gap-3"><span class="text-2xl">✏️</span><div><h3 class="text-base font-extrabold leading-snug">Editar Curso Obligatorio</h3><p class="text-xs text-rose-200 mt-0.5">Modifica los datos del catálogo oficial.</p></div></div>
                <button type="button" onclick="cerrarModalEditarCurso()" class="text-rose-200 hover:text-white p-1 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form id="form_editar_curso" action="" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div><label class="block text-xs font-bold text-gray-700 mb-1">Nombre oficial del curso:</label><input type="text" id="edit_nombre_curso" name="nombre" required class="w-full text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#691C32] focus:border-[#691C32] py-2.5 px-3"/></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1">Horas curriculares:</label><input type="number" id="edit_horas_curso" name="horas" min="1" max="100" required class="w-full text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#691C32] focus:border-[#691C32] py-2.5 px-3"/></div>
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="cerrarModalEditarCurso()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-[#691C32] hover:bg-[#4a1223] text-white rounded-xl text-xs font-bold shadow transition cursor-pointer">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    @php
        $cursosConConstancias = \Illuminate\Support\Facades\DB::table('cursos')->whereNotNull('evidencia')->where('evidencia', '!=', '')->select('nombre', \Illuminate\Support\Facades\DB::raw('count(*) as total_archivos'))->groupBy('nombre')->get();
    @endphp

    <div id="modal_zip_curso" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 hidden">
        <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-[#46081c] to-[#691C32] text-white p-5 flex items-center justify-between">
                <div class="flex items-center gap-3"><span class="text-2xl">📦</span><div><h3 class="text-base font-extrabold leading-snug">Descargar Constancias por Curso</h3><p class="text-xs text-rose-200 mt-0.5">Selecciona el curso para descargar el archivo .ZIP.</p></div></div>
                <button type="button" onclick="cerrarModalZipCurso()" class="text-rose-200 hover:text-white p-1 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="relative"><input type="text" id="input_filtrar_modal_zip" onkeyup="filtrarCursosEnModalZip()" placeholder="Buscar curso..." class="w-full text-xs pl-8 pr-3 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#691C32] focus:border-[#691C32]"/><span class="absolute left-2.5 top-2.5 text-xs text-gray-400">🔍</span></div>
                <div class="max-h-80 overflow-y-auto divide-y divide-gray-100 rounded-xl border border-gray-200 bg-gray-50/40 p-2 space-y-1">
                    @forelse($cursosConConstancias as $cZip)
                        <div class="fila-curso-zip flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 hover:border-gray-300 transition">
                            <div class="flex items-center gap-3 pr-2">
                                <span class="text-lg">{{ $obtenerIcono($cZip->nombre) }}</span>
                                <div><h5 class="nombre-c-zip text-xs font-bold text-gray-900 leading-tight">{{ $cZip->nombre }}</h5><span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md inline-block mt-1">✓ {{ $cZip->total_archivos }} constancia(s)</span></div>
                            </div>
                            <a href="{{ route('admin.descargar.zip.curso', ['nombre' => $cZip->nombre]) }}" class="px-3 py-1.5 bg-[#691C32] hover:bg-[#4a1223] text-white rounded-lg text-xs font-bold shadow-xs transition flex items-center gap-1.5 flex-shrink-0 cursor-pointer"><span>Descargar</span></a>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 text-xs">No hay cursos con constancias subidas aún.</div>
                    @endforelse
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="button" onclick="cerrarModalZipCurso()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl text-xs font-bold transition cursor-pointer">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="modal_preview_pdf" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4 hidden">
        <div class="bg-white rounded-2xl max-w-4xl w-full h-[85vh] shadow-2xl overflow-hidden border border-gray-200 flex flex-col">
            <div class="bg-gradient-to-r from-[#46081c] to-[#691C32] text-white p-4 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-2"><span class="text-xl">📄</span><h4 id="preview_pdf_titulo" class="text-sm font-extrabold truncate max-w-md">Vista Previa de Constancia</h4></div>
                <div class="flex items-center gap-2">
                    <a id="preview_pdf_descarga" href="#" target="_blank" class="px-3 py-1 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-bold transition">Abrir en pestaña</a>
                    <button type="button" onclick="cerrarModalPreviewPdf()" class="text-rose-200 hover:text-white p-1 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            </div>
            <div class="flex-1 w-full bg-gray-100 relative"><iframe id="preview_pdf_iframe" src="" class="w-full h-full border-0"></iframe></div>
        </div>
    </div>

    <form id="form_recordatorio_empleado" action="" method="POST" class="hidden">@csrf</form>

    <script>
        function cambiarModuloAdmin(seccion) {
            document.getElementById('sec-empleados').classList.add('hidden');
            document.getElementById('sec-estadisticas').classList.add('hidden');
            document.getElementById('sec-cursos').classList.add('hidden');
            ['btn-empleados', 'btn-estadisticas', 'btn-cursos'].forEach(btnId => {
                const b = document.getElementById(btnId);
                if (b) b.className = "w-full text-left p-3.5 rounded-xl transition-all duration-150 flex items-center justify-between bg-transparent text-gray-700 hover:bg-gray-100 font-semibold cursor-pointer";
            });
            document.getElementById('sec-' + seccion).classList.remove('hidden');
            document.getElementById('btn-' + seccion).className = "w-full text-left p-3.5 rounded-xl transition-all duration-150 flex items-center justify-between bg-[#691C32] text-white font-bold shadow-md cursor-pointer";
        }

        function toggleCursos(idFila, boton) {
            const fila = document.getElementById(idFila);
            if (!fila) return;

            const scrollTop = window.scrollY;
            const seAbre = fila.classList.contains('hidden');
            fila.classList.toggle('hidden');
            if (boton) boton.textContent = seAbre ? 'Ocultar Cursos ▴' : 'Ver Cursos ▾';

            requestAnimationFrame(() => {
                requestAnimationFrame(() => window.scrollTo(window.scrollX, scrollTop));
            });
        }

        let estadoFiltroActual = 'todos';
        function filtrarPorEstado(estado, btnElement) {
            estadoFiltroActual = estado;
            document.querySelectorAll('.btn-filtro-estado').forEach(b => {
                b.className = "btn-filtro-estado px-3 py-1.5 rounded-xl font-semibold bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition cursor-pointer flex items-center gap-1";
            });
            btnElement.className = "btn-filtro-estado px-3 py-1.5 rounded-xl font-bold bg-[#691C32] text-white shadow-xs transition cursor-pointer flex items-center gap-1";
            filtrarTabla();
        }

        function filtrarTabla() {
            const texto = document.getElementById('input_buscador').value.toLowerCase().trim();
            document.querySelectorAll('#tabla_empleados tbody tr.fila-empleado').forEach(fila => {
                const idEmp = fila.querySelector('td')?.innerText.trim();
                const filaCursos = document.getElementById('cursos-emp-' + idEmp);
                const textoCursos = filaCursos ? filaCursos.innerText.toLowerCase() : '';
                const contenidoCompleto = (fila.innerText + ' ' + textoCursos).toLowerCase();
                const coincideTexto = contenidoCompleto.includes(texto);
                const coincideEstado = (estadoFiltroActual === 'todos' || fila.getAttribute('data-estado') === estadoFiltroActual);

                if (coincideTexto && coincideEstado) {
                    fila.style.display = '';
                    if (texto.length > 2 && textoCursos.includes(texto) && filaCursos) filaCursos.classList.remove('hidden');
                } else {
                    fila.style.display = 'none';
                    if (filaCursos) filaCursos.classList.add('hidden');
                }
            });
        }

        function filtrarCursosCatalogo() {
            const texto = document.getElementById('input_buscar_curso').value.toLowerCase();
            document.querySelectorAll('#tabla_catalogo tbody tr.fila-curso-catalogo').forEach(fila => {
                const nombre = fila.querySelector('.nombre-curso-item')?.innerText.toLowerCase() || '';
                fila.style.display = nombre.includes(texto) ? '' : 'none';
            });
        }

        function abrirModalEditarCurso(id, nombre, horas) {
            document.getElementById('form_editar_curso').action = '/admin/cursos-obligatorios/' + id;
            document.getElementById('edit_nombre_curso').value = nombre;
            document.getElementById('edit_horas_curso').value = horas;
            document.getElementById('modal_editar_curso').classList.remove('hidden');
        }
        function cerrarModalEditarCurso() { document.getElementById('modal_editar_curso').classList.add('hidden'); }
        function abrirModalZipCurso() { document.getElementById('modal_zip_curso').classList.remove('hidden'); }
        function cerrarModalZipCurso() { document.getElementById('modal_zip_curso').classList.add('hidden'); }

        function filtrarCursosEnModalZip() {
            const texto = document.getElementById('input_filtrar_modal_zip').value.toLowerCase();
            document.querySelectorAll('#modal_zip_curso .fila-curso-zip').forEach(fila => {
                const nombre = fila.querySelector('.nombre-c-zip')?.innerText.toLowerCase() || '';
                fila.style.display = nombre.includes(texto) ? '' : 'none';
            });
        }

        function abrirModalPreviewPdf(url, titulo) {
            document.getElementById('preview_pdf_titulo').innerText = titulo;
            document.getElementById('preview_pdf_iframe').src = url;
            document.getElementById('preview_pdf_descarga').href = url;
            document.getElementById('modal_preview_pdf').classList.remove('hidden');
        }
        function cerrarModalPreviewPdf() {
            document.getElementById('preview_pdf_iframe').src = '';
            document.getElementById('modal_preview_pdf').classList.add('hidden');
        }

        function confirmarRecordatorio(idEmpleado, nombreEmpleado, correo) {
            Swal.fire({
                title: '¿Enviar recordatorio por correo?',
                html: `Se enviará un correo institucional a:<br><strong class="text-gray-900">${nombreEmpleado}</strong> (${correo})<br><span class="text-xs text-amber-700 mt-2 block">Se le notificará el avance de sus horas y los cursos normativos pendientes.</span>`,
                icon: 'question', iconColor: '#691C32', showCancelButton: true, confirmButtonColor: '#691C32', cancelButtonColor: '#6b7280', confirmButtonText: 'Sí, enviar recordatorio', cancelButtonText: 'Cancelar',
                customClass: { popup: 'rounded-2xl shadow-2xl border border-gray-100', confirmButton: 'rounded-xl text-xs font-bold px-4 py-2.5', cancelButton: 'rounded-xl text-xs font-medium px-4 py-2.5' }
            }).then(r => { if (r.isConfirmed) { const f = document.getElementById('form_recordatorio_empleado'); f.action = '/admin/recordatorio/' + idEmpleado; f.submit(); } });
        }

        function confirmarEliminarCursoCatalogo(id, nombre) {
            Swal.fire({
                title: '¿Eliminar curso del catálogo?',
                html: `¿Estás seguro de que deseas eliminar:<br><strong class="text-gray-900 font-extrabold mt-1 block">"${nombre}"</strong><br><span class="text-xs text-rose-600 font-medium block mt-1">Este curso ya no se solicitará a los colaboradores.</span>`,
                icon: 'warning', iconColor: '#dc2626', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280', confirmButtonText: 'Sí, eliminar del catálogo', cancelButtonText: 'Cancelar',
                customClass: { popup: 'rounded-2xl shadow-2xl border border-gray-100', confirmButton: 'rounded-xl text-xs font-bold px-4 py-2.5', cancelButton: 'rounded-xl text-xs font-medium px-4 py-2.5' }
            }).then(r => { if (r.isConfirmed) document.getElementById('form-del-curso-' + id).submit(); });
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if(session('seccion_admin') == 'cursos') cambiarModuloAdmin('cursos'); @endif
            @if(session('success')) Swal.fire({ icon: 'success', title: '¡Éxito!', html: {!! json_encode(session('success')) !!}, confirmButtonColor: '#691C32' }); @endif
            @if(session('error')) Swal.fire({ icon: 'error', title: 'Aviso', text: "{{ session('error') }}", confirmButtonColor: '#691C32' }); @endif

            Chart.defaults.font.family = "'Inter', 'Segoe UI', system-ui, sans-serif";
            Chart.defaults.color = '#64748B';

            new Chart(document.getElementById('graficaHoras'), {
                type: 'doughnut',
                data: {
                    labels: ['Meta Cumplida: {{ $pctMeta }}% ({{ $empleadosMetaCumplida }})', 'En Proceso: {{ $pctPendiente }}% ({{ max(0, $totalEmpleados - $empleadosMetaCumplida) }})'],
                    datasets: [{ data: [{{ $empleadosMetaCumplida }}, {{ max(0, $totalEmpleados - $empleadosMetaCumplida) }}], backgroundColor: ['#10B981', '#E2E8F0'], hoverBackgroundColor: ['#059669', '#CBD5E1'], borderWidth: 3, borderColor: '#FFFFFF' }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 18, font: { size: 11, weight: 'bold' } } } } },
                plugins: [{
                    id: 'textoCentroHoras',
                    beforeDraw(chart) {
                        const { ctx } = chart; const meta = chart.getDatasetMeta(0); if (!meta.data.length) return;
                        const x = meta.data[0].x, y = meta.data[0].y;
                        ctx.save(); ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                        ctx.font = 'bold 28px system-ui, sans-serif'; ctx.fillStyle = '#10B981'; ctx.fillText('{{ $pctMeta }}%', x, y - 6);
                        ctx.font = 'bold 10px system-ui, sans-serif'; ctx.fillStyle = '#94A3B8'; ctx.fillText('CUMPLIDO', x, y + 15); ctx.restore();
                    }
                }]
            });

            new Chart(document.getElementById('graficaParticipacion'), {
                type: 'doughnut',
                data: {
                    labels: ['Con Cursos: {{ $pctConCursos }}% ({{ $empleadosConCursos }})', 'Sin Cursos: {{ $pctSinCursos }}% ({{ $empleadosSinCursos }})'],
                    datasets: [{ data: [{{ $empleadosConCursos }}, {{ $empleadosSinCursos }}], backgroundColor: ['#691C32', '#F43F5E'], hoverBackgroundColor: ['#4e1223', '#E11D48'], borderWidth: 3, borderColor: '#FFFFFF' }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 18, font: { size: 11, weight: 'bold' } } } } },
                plugins: [{
                    id: 'textoCentroParticipacion',
                    beforeDraw(chart) {
                        const { ctx } = chart; const meta = chart.getDatasetMeta(0); if (!meta.data.length) return;
                        const x = meta.data[0].x, y = meta.data[0].y;
                        ctx.save(); ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                        ctx.font = 'bold 28px system-ui, sans-serif'; ctx.fillStyle = '#691C32'; ctx.fillText('{{ $pctConCursos }}%', x, y - 6);
                        ctx.font = 'bold 10px system-ui, sans-serif'; ctx.fillStyle = '#94A3B8'; ctx.fillText('ACTIVA', x, y + 15); ctx.restore();
                    }
                }]
            });

            const etiquetas = {!! json_encode(array_column($estadisticasCursos, 'nombre')) !!};
            const datos = {!! json_encode(array_column($estadisticasCursos, 'acreditados')) !!};
            new Chart(document.getElementById('graficaCursos'), {
                type: 'bar',
                data: { labels: etiquetas, datasets: [{ label: 'Empleados acreditados', data: datos, backgroundColor: '#691C32', hoverBackgroundColor: '#8B2644', borderRadius: 8, barThickness: 20 }] },
                options: {
                    indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                    scales: {
                        x: { beginAtZero: true, max: {{ max(1, $totalEmpleados) }}, ticks: { precision: 0, stepSize: 1 }, grid: { color: '#F1F5F9' } },
                        y: { grid: { display: false }, ticks: { font: { size: 11, weight: 'bold' }, color: '#1E293B' } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1E293B', padding: 10, cornerRadius: 8,
                            callbacks: { label: function(c) { const t = {{ $totalEmpleados }}, v = c.parsed.x, p = t > 0 ? Math.round((v / t) * 100) : 0; return ` ${v} de ${t} empleado(s) (${p}%)`; } }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
