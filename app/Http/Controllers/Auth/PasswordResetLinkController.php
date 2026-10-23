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
    // 1. Validar el campo 'correo'
    $request->validate([
        'correo' => ['required', 'email'],
    ]);

    // 2. Buscar por 'correo' en la base de datos
    $status = Password::sendResetLink(
        $request->only('correo')
    );

    return $status == Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withInput($request->only('correo'))
                        ->withErrors(['correo' => __($status)]);
}
}
