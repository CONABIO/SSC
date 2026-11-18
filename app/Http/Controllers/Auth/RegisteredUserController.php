<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Mail\RegistroExitosoMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

   public function store(Request $request): RedirectResponse
{
    $sinMuchosEspacios = function (string $attribute, mixed $value, \Closure $fail) {
    if ($value === null || $value === '') {
        return;
    }

    $limpio = trim((string) $value);

    if (preg_match('/\s{2,}/u', $limpio) || preg_match_all('/\s/u', $limpio) > 2) {
        $fail('El campo no debe contener espacios seguidos ni más de 2 espacios en blanco.');
    }
};
    $request->validate([
        'idEmpleado' => [
            'required',
            'integer',
            'min:1',
            'max:1000000000',
            'unique:empleado,idEmpleado',
        ],
        'nombre'     => ['required', 'string', 'max:50'],
        'apellidoP'  => ['required', 'string', 'max:50'],
        'apellidoM'  => ['nullable', 'string', 'max:50'],
        'correo'     => [
            'required',
            'string',
            'email',
            'max:100',
            'unique:empleado,correo',
            'regex:/^[A-Z0-9._%+-]+@semarnat\.gob\.mx$/i',
        ],
        'contrasena' => ['required', 'string', 'max:255', 'confirmed', Rules\Password::defaults()],
    ],
    [
        'idEmpleado.required'  => 'El número de empleado es obligatorio.',
        'idEmpleado.integer'   => 'El número de empleado debe ser un número entero (sin puntos ni letras).',
        'idEmpleado.min'       => 'El número de empleado debe ser mayor a 0.',
        'idEmpleado.max'       => 'El número de empleado no puede ser mayor a 1000000000.',
        'idEmpleado.unique'    => 'El número de empleado que ingresaste ya fue registrado anteriormente.',

        'nombre.required'      => 'El nombre es obligatorio.',
        'nombre.max'           => 'El nombre no puede superar los 50 caracteres.',

        'apellidoP.required'   => 'El primer apellido es obligatorio.',
        'apellidoP.max'        => 'El primer apellido no puede superar los 50 caracteres.',

        'apellidoM.max'        => 'El segundo apellido no puede superar los 50 caracteres.',

        'correo.required'      => 'El correo institucional es obligatorio.',
        'correo.email'         => 'Ingresa un formato de correo válido.',
        'correo.max'           => 'El correo no puede superar los 100 caracteres.',
        'correo.unique'        => 'Este correo electrónico ya fue registrado anteriormente.',
        'correo.regex'         => 'El correo debe pertenecer al dominio @semarnat.gob.mx.',

        'contrasena.required'  => 'El campo de contraseña es obligatorio.',
        'contrasena.confirmed' => 'Las contraseñas no coinciden.',
        'contrasena.min'       => 'La contraseña debe tener al menos :min caracteres.',
        'contrasena.max'       => 'La contraseña es demasiado larga.',
    ]);

    $empleado = Empleado::create([
        'idEmpleado' => $request->idEmpleado,
        'nombre'     => $request->nombre,
        'apellidoP'  => $request->apellidoP,
        'apellidoM'  => $request->apellidoM,
        'correo'     => $request->correo,
        'contrasena' => Hash::make($request->contrasena),
        'idRol'      => 2,
    ]);

    event(new Registered($empleado));

    Mail::to($empleado->correo)->send(new RegistroExitosoMail($empleado));

    return redirect()->route('login')->with('status', '¡Registro exitoso! Por favor inicia sesión con tu cuenta.');
}
}
