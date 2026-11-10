<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $empleadosBD = DB::table('empleado')->where('idRol', 2)->get();
        $todosLosCursos = DB::table('cursos')->get();
        $cursosCatalogo = DB::table('cat_cursosemarnat')->get();
        $totalCatalogoObligatorios = $cursosCatalogo->count();
        $empleados = $empleadosBD->map(function ($emp) use ($todosLosCursos, $cursosCatalogo) {
            $id = $emp->idEmpleado ?? $emp->id;
            $emp->cursos = $todosLosCursos->where('idEmpleado', $id);
            return $emp;
        });
        $totalEmpleados = $empleados->count();
        $empleadosConCursos = 0;
        $empleadosSinCursos = 0;
        $empleadosMetaCumplida = 0;
        $empleadosObligatoriosCompletos = 0;
        foreach ($empleados as $emp) {
            $totalHoras = $emp->cursos->sum('horas');
            $cantObligatorios = $emp->cursos->where('obligatorio', 1)->unique('nombre')->count();

            if ($emp->cursos->count() > 0) {
                $empleadosConCursos++;
            } else {
                $empleadosSinCursos++;
            }

            if ($totalHoras >= 40) {
                $empleadosMetaCumplida++;
            }

            if ($totalCatalogoObligatorios > 0 && $cantObligatorios >= $totalCatalogoObligatorios) {
                $empleadosObligatoriosCompletos++;
            }
        }
        $estadisticasCursos = [];
        foreach ($cursosCatalogo as $cat) {
            $acreditados = $todosLosCursos->where('nombre', $cat->nombre)
                ->where('obligatorio', 1)
                ->unique('idEmpleado')
                ->count();

            $estadisticasCursos[] = [
                'nombre' => $cat->nombre,
                'acreditados' => $acreditados,
                'pendientes' => max(0, $totalEmpleados - $acreditados),
            ];
        }
        return view('dashboardAdmin', compact(
            'empleados',
            'totalEmpleados',
            'empleadosConCursos',
            'empleadosSinCursos',
            'empleadosMetaCumplida',
            'empleadosObligatoriosCompletos',
            'totalCatalogoObligatorios',
            'estadisticasCursos',
            'cursosCatalogo'
        ));
    }



    public function storeCursoObligatorio(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'horas'  => 'required|integer|min:1|max:100',
        ]);
        DB::table('cat_cursosemarnat')->insert([
            'nombre' => trim($request->nombre),
            'horas'  => $request->horas,
        ]);
        return redirect()->back()
            ->with('success', 'Curso obligatorio agregado exitosamente al catálogo.')
            ->with('seccion_admin', 'cursos');
    }

    public function destroyCursoObligatorio($id)
    {
        $columnaId = DB::getSchemaBuilder()->hasColumn('cat_cursosemarnat', 'idCurso') ? 'idCurso' : 'id';
        DB::table('cat_cursosemarnat')->where($columnaId, $id)->delete();
        return redirect()->back()
            ->with('success', 'Curso eliminado del catálogo.')
            ->with('seccion_admin', 'cursos');
    }


    public function exportarExcel(Request $request)
    {
        $tipo = $request->get('tipo', 'todos');
        $empleadosBD = DB::table('empleado')->get();
        $todosLosCursos = DB::table('cursos')->get();
        $cursosCatalogo = DB::table('cat_cursosemarnat')->get();
        $totalCatalogo = $cursosCatalogo->count();
        $nombresCatalogo = $cursosCatalogo->pluck('nombre')->toArray();
        $datos = $empleadosBD->map(function ($emp) use ($todosLosCursos, $nombresCatalogo, $totalCatalogo) {
            $id = $emp->idEmpleado ?? $emp->id;
            $cursosEmp = $todosLosCursos->where('idEmpleado', $id);
            $totalHoras = $cursosEmp->sum('horas');
            $obligatoriosAcreditados = $cursosEmp->where('obligatorio', 1)->pluck('nombre')->unique()->toArray();
            $cantObligatorios = count($obligatoriosAcreditados);
            $faltantes = array_diff($nombresCatalogo, $obligatoriosAcreditados);
            return [
                'idEmpleado'          => $id,
                'nombreCompleto'      => trim($emp->nombre . ' ' . $emp->apellidoP . ' ' . ($emp->apellidoM ?? '')),
                'correo'              => $emp->correo,
                'totalHoras'          => $totalHoras,
                'cumpleMeta40'        => $totalHoras >= 40 ? 'SÍ' : 'NO',
                'cantCursos'          => $cursosEmp->count(),
                'obligatoriosProg'    => "{$cantObligatorios} de {$totalCatalogo}",
                'cumpleObligatorios'  => ($totalCatalogo > 0 && $cantObligatorios >= $totalCatalogo) ? 'SÍ' : 'NO',
                'cursosFaltantes'     => empty($faltantes) ? 'Ninguno (Acreditados al 100%)' : implode(' | ', $faltantes),
                'tieneCursos'         => $cursosEmp->count() > 0,
            ];
        });
        if ($tipo === 'meta_cumplida') {
            $datos = $datos->where('totalHoras', '>=', 40);
            $nombreArchivo = 'Reporte_Meta_40_Horas_Cumplida.csv';
        } elseif ($tipo === 'pendientes_horas') {
            $datos = $datos->where('totalHoras', '<', 40);
            $nombreArchivo = 'Reporte_Pendientes_Meta_40_Horas.csv';
        } elseif ($tipo === 'obligatorios_listos') {
            $datos = $datos->where('cumpleObligatorios', 'SÍ');
            $nombreArchivo = 'Reporte_Cursos_Obligatorios_Concluidos.csv';
        } elseif ($tipo === 'sin_iniciar') {
            $datos = $datos->where('tieneCursos', false);
            $nombreArchivo = 'Reporte_Colaboradores_Sin_Cursos.csv';
        } else {
            $nombreArchivo = 'Reporte_General_Capacitacion_CONABIO.csv';
        }
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$nombreArchivo}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        return response()->stream(function () use ($datos) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output, [
                'No. Empleado',
                'Nombre Completo',
                'Correo Institucional',
                'Total Horas',
                '¿Meta 40 hrs Cumplida?',
                'Total Cursos Registrados',
                'Cursos Obligatorios Acreditados',
                '¿Obligatorios al 100%?',
                'Cursos Obligatorios Faltantes'
            ]);

            foreach ($datos as $fila) {
                fputcsv($output, [
                    $fila['idEmpleado'],
                    $fila['nombreCompleto'],
                    $fila['correo'],
                    $fila['totalHoras'],
                    $fila['cumpleMeta40'],
                    $fila['cantCursos'],
                    $fila['obligatoriosProg'],
                    $fila['cumpleObligatorios'],
                    $fila['cursosFaltantes'],
                ]);
            }

            fclose($output);
        }, 200, $headers);
    }


    public function descargarZipEmpleado($idEmpleado)
    {
        $empleado = DB::table('empleado')->where('idEmpleado', $idEmpleado)->first();
        if (!$empleado) {
            return back()->with('error', 'Empleado no encontrado.');
        }
        $cursos = DB::table('cursos')
            ->where('idEmpleado', $idEmpleado)
            ->whereNotNull('evidencia')
            ->get();
        if ($cursos->isEmpty()) {
            return back()->with('error', 'El empleado ' . $empleado->nombre . ' no cuenta con constancias cargadas.');
        }
        $zip = new \ZipArchive();
        $nombreZip = 'Constancias_' . Str::slug($empleado->nombre . '_' . $empleado->apellidoP) . '_' . date('Ymd') . '.zip';
        $tempFile = tempnam(sys_get_temp_dir(), 'zip_emp_');
        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'No se pudo generar el archivo comprimido.');
        }
        $agregados = 0;
        foreach ($cursos as $c) {
            if ($c->evidencia && Storage::disk('public')->exists($c->evidencia)) {
                $rutaFisica = Storage::disk('public')->path($c->evidencia);
                $nombreArchivoEnZip = $this->nombreConstanciaEnZip($idEmpleado, $empleado, $c, $rutaFisica);
                if ($zip->addFile($rutaFisica, $nombreArchivoEnZip)) {
                    $agregados++;
                }
            }
        }
        $zip->close();
        if ($agregados === 0) {
            @unlink($tempFile);
            return back()->with('error', 'Los archivos de evidencia no se encontraron físicamente en el servidor.');
        }
        return response()->download($tempFile, $nombreZip)->deleteFileAfterSend(true);
    }

    public function descargarZipCurso(Request $request)
    {
        $nombreCurso = $request->query('nombre');
        if (!$nombreCurso) {
            return back()->with('error', 'Curso no especificado.');
        }
        $cursos = DB::table('cursos')
            ->where('nombre', $nombreCurso)
            ->whereNotNull('evidencia')
            ->get();
        if ($cursos->isEmpty()) {
            return back()->with('error', 'Aún no hay constancias entregadas para el curso "' . $nombreCurso . '".');
        }
        $empleados = DB::table('empleado')->get()->keyBy('idEmpleado');
        $zip = new ZipArchive();
        $nombreZip = 'Constancias_Curso_' . Str::slug($nombreCurso) . '_' . date('Ymd') . '.zip';
        $tempFile = tempnam(sys_get_temp_dir(), 'zip_curso_');
        if ($zip->open($tempFile, \ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'No se pudo generar el archivo comprimido.');
        }
        $agregados = 0;
        foreach ($cursos as $c) {
            if ($c->evidencia && Storage::disk('public')->exists($c->evidencia)) {
                $rutaFisica = Storage::disk('public')->path($c->evidencia);
                $emp = $empleados->get($c->idEmpleado);
                $nombreArchivoEnZip = $this->nombreConstanciaEnZip($c->idEmpleado, $emp, $c, $rutaFisica);
                if ($zip->addFile($rutaFisica, $nombreArchivoEnZip)) {
                    $agregados++;
                }
            }
        }
        $zip->close();
        if ($agregados === 0) {
            @unlink($tempFile);
            return back()->with('error', 'Los archivos de evidencia no se encontraron físicamente en el servidor.');
        }
        return response()->download($tempFile, $nombreZip)->deleteFileAfterSend(true);
    }

    private function nombreConstanciaEnZip($idEmpleado, $empleado, $curso, $rutaFisica)
    {
        $normalizar = fn ($valor, $respaldo) => Str::slug((string) $valor, '_') ?: $respaldo;
        $ext = pathinfo($rutaFisica, PATHINFO_EXTENSION) ?: 'pdf';
        $nombreEmpleado = $empleado->nombre ?? '';
        $apellidoPaterno = $empleado->apellidoP ?? '';
        $apellidoMaterno = $empleado->apellidoM ?? '';

        return implode('_', [
            $normalizar($idEmpleado, 'sin_id'),
            $normalizar($nombreEmpleado, 'sin_nombre'),
            $normalizar($apellidoPaterno, 'sin_apellido_paterno'),
            $normalizar($apellidoMaterno, 'sin_apellido_materno'),
            $normalizar($curso->nombre ?? '', 'sin_nombre_curso'),
        ]) . '.' . $ext;
    }

    public function updateCursoObligatorio(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'horas'  => 'required|integer|min:1|max:100',
        ]);
        $columnaId = DB::getSchemaBuilder()->hasColumn('cat_cursosemarnat', 'idCurso') ? 'idCurso' : 'id';
        $cursoAntiguo = DB::table('cat_cursosemarnat')->where($columnaId, $id)->first();
        if ($cursoAntiguo) {
            DB::table('cat_cursosemarnat')->where($columnaId, $id)->update([
                'nombre' => trim($request->nombre),
                'horas'  => $request->horas,
            ]);
            DB::table('cursos')
                ->where('nombre', $cursoAntiguo->nombre)
                ->where('obligatorio', 1)
                ->update([
                    'nombre' => trim($request->nombre),
                    'horas'  => $request->horas,
                ]);
        }
        return redirect()->back()
            ->with('success', 'Curso obligatorio actualizado exitosamente.')
            ->with('seccion_admin', 'cursos');
    }


    public function enviarRecordatorio($idEmpleado)
    {
        $empleado = DB::table('empleado')->where('idEmpleado', $idEmpleado)->first();
        if (!$empleado || empty($empleado->correo)) {
            return back()->with('error', 'No se encontró correo válido para este colaborador.');
        }
        $cursosEmpleado = DB::table('cursos')->where('idEmpleado', $idEmpleado)->get();
        $totalHoras = $cursosEmpleado->sum('horas');
        $catalogo = DB::table('cat_cursosemarnat')->get();
        $acreditados = $cursosEmpleado->where('obligatorio', 1)->pluck('nombre')->toArray();
        $faltantes = $catalogo->filter(fn($c) => !in_array($c->nombre, $acreditados));
        \Illuminate\Support\Facades\Mail::send('emails.recordatorio', [
            'empleado'   => $empleado,
            'totalHoras' => $totalHoras,
            'faltantes'  => $faltantes,
        ], function ($message) use ($empleado) {
            $message->to($empleado->correo)
                ->subject('Recordatorio: Avance de capacitación obligatoria y horas anuales- CONABIO');
        });
        return back()->with('success', 'Recordatorio enviado exitosamente a <strong>' . $empleado->correo . '</strong>.');
    }
}
