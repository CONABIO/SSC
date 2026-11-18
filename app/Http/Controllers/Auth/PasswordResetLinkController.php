<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'correo' => ['required', 'email'],
        ], [
            'correo.required' => 'El correo institucional es obligatorio.',
            'correo.email'    => 'Ingresa un formato de correo electrónico válido.',
        ]);

        $status = Password::sendResetLink(
            $request->only('correo')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Hemos enviado a tu correo institucional el enlace para restablecer tu contraseña.');
        }

        $mensajeError = match ($status) {
            Password::INVALID_USER    => 'No encontramos ningún usuario registrado con ese correo electrónico.',
            Password::RESET_THROTTLED => 'Por favor espera unos momentos antes de volver a intentarlo.',
            default                   => 'No se pudo enviar el enlace de recuperación. Intenta de nuevo.',
        };

        return back()
            ->withInput($request->only('correo'))
            ->withErrors(['correo' => $mensajeError]);
    }
}
