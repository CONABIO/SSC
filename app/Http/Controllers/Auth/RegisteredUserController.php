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
        $request->validate([
            'idEmpleado' => ['required', 'numeric', 'unique:empleado,idEmpleado'],
            'nombre'     => ['required', 'string', 'max:255'],
            'apellidoP'  => ['required', 'string', 'max:255'],
            'apellidoM'  => ['nullable', 'string', 'max:255'],
            'correo'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:empleado,correo'],
            'contrasena' => ['required', 'confirmed', Rules\Password::defaults()],
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
