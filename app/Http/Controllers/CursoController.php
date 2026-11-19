<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CursoController extends Controller
{
    public function index()
    {
        if (Auth::user()->idRol == 1) {
            return redirect()->route('dashboardAdmin');
        }

        $idEmpleado = Auth::user()->idEmpleado ?? Auth::id();

        $cursosCatalogo = DB::table('cat_cursosemarnat')->get();

        $otrosCursos = Curso::where('idEmpleado', $idEmpleado)
            ->where('obligatorio', 0)
            ->get();

        $obligatoriosEntregados = Curso::where('idEmpleado', $idEmpleado)
            ->where('obligatorio', 1)
            ->pluck('nombre')
            ->toArray();

        $obligatoriosCompletados = count($obligatoriosEntregados);

        $totalHoras = Curso::where('idEmpleado', $idEmpleado)->sum('horas');

        return view('dashboard', compact(
            'cursosCatalogo',
            'otrosCursos',
            'obligatoriosCompletados',
            'obligatoriosEntregados',
            'totalHoras'
        ));
    }

    public function storeOtro(Request $request)
    {
        $request->validate([
            'nombre_curso'    => 'required|string|max:255',
            'horas'           => 'required|integer|min:1',
            'calificacion'    => 'required|numeric|min:0|max:10',
            'constancia_otro' => 'required|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'correo_copia'    => [
                'nullable',
                'required_if:enviar_correo,1',
                'email',
                'regex:/^[A-Za-z0-9._%+\-]+@conabio\.gob\.mx$/i',
            ],
        ], [
            'correo_copia.required_if' => 'Debes ingresar un correo @conabio.gob.mx para enviar el comprobante.',
            'correo_copia.email'       => 'El correo para el comprobante no es válido.',
            'correo_copia.regex'       => 'El correo para el comprobante debe pertenecer al dominio @conabio.gob.mx.',
        ]);

        $usuario = Auth::user();
        $rutaArchivo = null;

        if ($request->hasFile('constancia_otro')) {
            $rutaArchivo = $request->file('constancia_otro')->store('evidencias', 'public');
        }

        $curso = Curso::create([
            'idEmpleado'       => $usuario->idEmpleado ?? $usuario->id,
            'nombre'           => $request->nombre_curso,
            'obligatorio'      => 0,
            'validadoSemarnat' => 0,
            'horas'            => $request->horas,
            'periodo'          => $request->periodo,
            'calificacion'     => $request->calificacion,
            'evidencia'        => $rutaArchivo,
        ]);
        if ($request->boolean('enviar_correo')) {
            $correoComprobante = strtolower(trim($request->input('correo_copia')));
            $archivo = $request->file('constancia_otro');
            Mail::to('centro.capacitacion@semarnat.gob.mx')->send(
                new \App\Mail\ConstanciaObligatorioMailable($usuario, $curso, $archivo)
            );
            Mail::to($correoComprobante)->send(
                new \App\Mail\ConstanciaObligatorioMailable(
                    $usuario,
                    $curso,
                    $archivo,
                    'talento.humano@conabio.gob.mx',
                    'Talento Humano CONABIO'
                )
            );

            $mensaje = "Constancia enviada a centro.capacitacion@semarnat.gob.mx y comprobante enviado a {$correoComprobante}.";
        } else {
            $mensaje = 'Curso registrado exitosamente.';
        }

        return redirect()->back()
            ->with('success', $mensaje)
            ->with('seccion', 'otros');
    }

    public function validarSemarnat(Request $request)
    {
        $request->validate([
            'curso_id'             => 'required',
            'comprobante_semarnat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $curso = Curso::findOrFail($request->curso_id);

        $ruta = $request->file('comprobante_semarnat')->store('validaciones_semarnat', 'public');

        $curso->validadoSemarnat = 1;
        $curso->validacion = $ruta;
        $curso->save();

        return redirect()->back()
            ->with('success', 'El curso "' . $curso->nombre . '" ha sido marcado como validado por SEMARNAT.')
            ->with('seccion', 'semarnat');
    }

    public function destroy($id)
    {
        $idEmpleado = Auth::user()->idEmpleado ?? Auth::id();
        $curso = Curso::where('id', $id)
            ->where('idEmpleado', $idEmpleado)
            ->firstOrFail();

        if ($curso->evidencia && Storage::disk('public')->exists($curso->evidencia)) {
            Storage::disk('public')->delete($curso->evidencia);
        }
        if ($curso->validacion && Storage::disk('public')->exists($curso->validacion)) {
            Storage::disk('public')->delete($curso->validacion);
        }

        $curso->delete();

        return redirect()->back()
            ->with('success', 'Curso eliminado exitosamente.')
            ->with('seccion', 'otros');
    }

    public function storeObligatorio(Request $request)
    {
        $request->validate([
            'curso_id'     => 'required|integer',
            'nombre_curso' => 'required|string|max:255',
            'horas'        => 'required|integer|min:1',
            'calificacion' => 'required|numeric|min:0|max:10',
            'periodo'      => 'required|integer|between:2025,2030',
            'constancia'   => 'required|file|mimes:pdf|max:5120',
            'correo_copia' => [
                'nullable',
                'required_if:enviar_correo,1',
                'email',
                'regex:/^[A-Za-z0-9._%+\-]+@conabio\.gob\.mx$/i',
            ],
        ], [
            'correo_copia.required_if' => 'Debes ingresar un correo @conabio.gob.mx para enviar el comprobante.',
            'correo_copia.email'       => 'El correo para el comprobante no es válido.',
            'correo_copia.regex'       => 'El correo para el comprobante debe pertenecer al dominio @conabio.gob.mx.',
        ]);

        $usuario = Auth::user();
        $rutaArchivo = null;
        if ($request->hasFile('constancia')) {
            $rutaArchivo = $request->file('constancia')->store('evidencias', 'public');
        }

        $curso = Curso::create([
            'idEmpleado'       => $usuario->idEmpleado ?? $usuario->id,
            'nombre'           => $request->nombre_curso,
            'obligatorio'      => 1,
            'validadoSemarnat' => 0,
            'horas'            => $request->horas,
            'calificacion'     => $request->calificacion,
            'periodo'          => $request->periodo,
            'evidencia'        => $rutaArchivo,
        ]);
        $curso->periodo = $request->periodo;

        if ($request->boolean('enviar_correo')) {
            $correoComprobante = strtolower(trim($request->input('correo_copia')));
            $archivo = $request->file('constancia');

            Mail::to('centro.capacitacion@semarnat.gob.mx')->send(
                new \App\Mail\ConstanciaObligatorioMailable($usuario, $curso, $archivo)
            );

            Mail::to($correoComprobante)->send(
                new \App\Mail\ConstanciaObligatorioMailable(
                    $usuario,
                    $curso,
                    $archivo,
                    'talento.humano@conabio.gob.mx',
                    'Talento Humano CONABIO'
                )
            );

            $mensaje = "Constancia enviada a centro.capacitacion@semarnat.gob.mx y comprobante enviado a {$correoComprobante}.";
        } else {
            $mensaje = 'Curso obligatorio acreditado exitosamente.';
        }

        return redirect()->back()
            ->with('success', $mensaje)
            ->with('seccion', 'obligatorios');
    }

    public function verEvidencia($id)
    {
        $curso = \App\Models\Curso::findOrFail($id);
        $user = Auth::user();
        $idEmpleadoActual = $user->idEmpleado ?? $user->id;

        if ($curso->idEmpleado != $idEmpleadoActual && $user->idRol != 1) {
            abort(403, 'No tienes permiso para ver esta constancia.');
        }

        if (!$curso->evidencia) {
            abort(404, 'El registro no tiene constancia asociada.');
        }

        if (Storage::disk('public')->exists($curso->evidencia)) {
            return response()->file(Storage::disk('public')->path($curso->evidencia));
        }

        if (Storage::disk('local')->exists($curso->evidencia)) {
            return response()->file(Storage::disk('local')->path($curso->evidencia));
        }

        abort(404, 'La constancia física no se encuentra en el servidor.');
    }

    public function verValidacion($id)
    {
        $curso = Curso::findOrFail($id);
        $usuario = Auth::user();
        $idEmpleadoActual = $usuario->idEmpleado ?? $usuario->id;

        if ($curso->idEmpleado != $idEmpleadoActual && $user->idRol != 1) {
            abort(403, 'No tienes permiso para ver este comprobante.');
        }

        if (!$curso->validacion) {
            abort(404, 'El curso no tiene respuesta SEMARNAT asociada.');
        }

        if (Storage::disk('public')->exists($curso->validacion)) {
            return response()->file(Storage::disk('public')->path($curso->validacion));
        }

        if (Storage::disk('local')->exists($curso->validacion)) {
            return response()->file(Storage::disk('local')->path($curso->validacion));
        }

        abort(404, 'El comprobante de validación no se encuentra en el servidor.');
    }
}
