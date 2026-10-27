<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'correo' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->correo)
            ->first();

        if (! $tokenRecord && \Illuminate\Support\Facades\Schema::hasTable('password_resets')) {
            $tokenRecord = DB::table('password_resets')
                ->where('email', $request->correo)
                ->first();
        }

        if (! $tokenRecord || ! Hash::check($request->token, $tokenRecord->token)) {
            return back()->withErrors(['correo' => 'El enlace de recuperación es inválido o ya fue utilizado.']);
        }

        $empleado = DB::table('empleado')->where('correo', $request->correo)->first();

        if (! $empleado) {
            return back()->withErrors(['correo' => 'No se encontró ningún empleado registrado con este correo.']);
        }

        DB::table('empleado')
            ->where('correo', $request->correo)
            ->update([
                'contrasena' => Hash::make($request->password),
            ]);

        DB::table('password_reset_tokens')->where('email', $request->correo)->delete();
        if (\Illuminate\Support\Facades\Schema::hasTable('password_resets')) {
            DB::table('password_resets')->where('email', $request->correo)->delete();
        }

        return redirect()->route('login')->with('status', 'Tu contraseña ha sido actualizada con éxito.');
    }
}
