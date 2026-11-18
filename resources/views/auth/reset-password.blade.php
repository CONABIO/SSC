<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer Contraseña | CONABIO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Montserrat', 'sans-serif'] },
                    colors: {
                        vino: {
                            950: '#22040d',
                            900: '#380616',
                            850: '#4d0a1f',
                            800: '#691C32',
                            700: '#85213e',
                            600: '#9d2449',
                            500: '#b83258',
                        },
                        dorado: {
                            300: '#E6D7BD',
                            400: '#D4C19C',
                            500: '#BC955C',
                            600: '#9E773E'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <div class="lg:w-4/12 xl:w-[35%] bg-gradient-to-b from-vino-900 via-vino-850 to-vino-950 text-white flex flex-col justify-between p-7 sm:p-9 relative overflow-hidden border-r border-vino-700/40">
            <img src="https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?q=80&w=1200&auto=format&fit=crop"
                alt="Naturaleza Mexicana"
                class="absolute inset-0 w-full h-full object-cover opacity-10 mix-blend-luminosity pointer-events-none" />
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-dorado-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 w-full py-4">
                <div class="flex flex-col items-center justify-center mb-8">
                    <img src="{{ asset('images/logo_conabio_transparente.png') }}" alt="Logo CONABIO"
                        class="h-32 sm:h-36 w-auto mx-auto block object-contain brightness-0 invert drop-shadow-xl transition-all" />
                </div>
                <div class="pt-8 border-t-2 border-dorado-500/30 text-center sm:text-left">
                    <h1 class="text-3xl sm:text-4xl lg:text-4xl font-black text-white leading-tight tracking-tight mt-12">
                        Sistema de Seguimiento de <span class="text-dorado-400">Capacitación</span>
                    </h1>
                    <p class="text-sm sm:text-base text-rose-100/90 mt-4 leading-relaxed font-normal">
                        Control de Profesionalización de las Personas Servidoras Públicas asignadas a la Comisión Nacional para el Conocimiento y Uso de la Biodiversidad.
                    </p>
                </div>
            </div>
            <div class="relative z-10 text-[11px] text-rose-200/60 border-t border-vino-800/80 pt-4 mt-6">
                © {{ date('Y') }} CONABIO • Comisión Nacional para el Conocimiento y Uso de la Biodiversidad.
            </div>
        </div>

        <div class="lg:w-8/12 xl:w-[65%] flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-white">
            <div class="w-full max-w-md space-y-6">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-vino-800 mb-4 shadow-sm">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                        Restablecer Contraseña
                    </h2>
                    <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">
                        Crea una nueva contraseña segura para ingresar a tu cuenta institucional.
                    </p>
                </div>

                <form id="resetForm" method="POST" action="{{ route('password.store') }}" class="space-y-4" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    @php
                        $valorCorreo = old('correo', old('email', request()->query('email') ?? request()->email ?? request()->query('correo') ?? request()->correo));
                    @endphp
                    <input type="hidden" name="correo" value="{{ $valorCorreo }}">
                    <input type="hidden" name="email" value="{{ $valorCorreo }}">

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Nueva Contraseña
                        </label>
                        <div class="relative rounded-lg">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password"
                                minlength="8" maxlength="255" placeholder="••••••••••••" required autofocus
                                autocomplete="new-password"
                                class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                        </div>
                        <p class="mt-1.5 text-[11px] text-gray-500">
                            Debe tener mínimo 8 caracteres e incluir letras mayúsculas, minúsculas, números y signos (Ej. <span class="font-semibold text-gray-700">Ejempl0-894</span>).
                        </p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Confirmar Nueva Contraseña
                        </label>
                        <div class="relative rounded-lg">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                minlength="8" maxlength="255" placeholder="••••••••••••" required
                                autocomplete="new-password"
                                class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-lg bg-vino-800 hover:bg-vino-900 active:bg-vino-950 text-white font-bold text-sm shadow-md hover:shadow-lg transition duration-150 cursor-pointer">
                            <span>Restablecer Contraseña</span>
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="pt-4 border-t border-gray-100 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-vino-800 hover:text-vino-950 hover:underline">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Volver al inicio de sesión</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const errores = @json($errors->all());
                function traducirError(msg) {
                    const traducciones = {
                        'This password reset token is invalid.': 'El enlace o token para restablecer la contraseña no es válido o ya expiró. Solicita uno nuevo.',
                        'passwords.token': 'El enlace o token para restablecer la contraseña no es válido o ya expiró. Solicita uno nuevo.',
                        "We can't find a user with that email address.": 'No encontramos ninguna cuenta registrada con ese correo electrónico.',
                        'passwords.user': 'No encontramos ninguna cuenta registrada con ese correo electrónico.',
                        'Please wait before retrying.': 'Por favor espera unos momentos antes de intentarlo de nuevo.',
                        'passwords.throttled': 'Por favor espera unos momentos antes de intentarlo de nuevo.',
                        'The token field is required.': 'El token de recuperación es obligatorio.',
                        'The token is required.': 'El token de recuperación es obligatorio.',
                        'The email field is required.': 'El correo electrónico es obligatorio.',
                        'The email is required.': 'El correo electrónico es obligatorio.',
                        'The correo field is required.': 'El correo electrónico es obligatorio.',
                        'The email field must be a valid email address.': 'El correo electrónico no tiene un formato válido.',
                        'The email must be a valid email address.': 'El correo electrónico no tiene un formato válido.',
                        'The correo field must be a valid email address.': 'El correo electrónico no tiene un formato válido.',
                        'The password field is required.': 'Debes ingresar una nueva contraseña.',
                        'The password is required.': 'Debes ingresar una nueva contraseña.',
                        'The password field must be a string.': 'La contraseña debe ser un texto válido.',
                        'The password field confirmation does not match.': 'La confirmación de la contraseña no coincide.',
                        'The password confirmation does not match.': 'La confirmación de la contraseña no coincide.',
                        'The password field must contain at least one uppercase and one lowercase letter.': 'La contraseña debe incluir al menos una letra mayúscula y una minúscula.',
                        'The password field must contain at least one letter.': 'La contraseña debe incluir al menos una letra.',
                        'The password field must contain at least one symbol.': 'La contraseña debe incluir al menos un signo o símbolo.',
                        'The password field must contain at least one number.': 'La contraseña debe incluir al menos un número.',
                        'The given password has appeared in a data leak. Please choose a different password.': 'Esta contraseña ha aparecido en filtraciones de seguridad. Por favor elige una diferente.'
                    };

                    if (traducciones[msg]) {
                        return traducciones[msg];
                    }
                    const matchMin = msg.match(/The password (?:field )?must be at least (\d+) characters\./i);
                    if (matchMin) {
                        return `La contraseña debe tener al menos ${matchMin[1]} caracteres.`;
                    }
                    const matchMax = msg.match(/The password (?:field )?must not be greater than (\d+) characters\./i);
                    if (matchMax) {
                        return `La contraseña no debe tener más de ${matchMax[1]} caracteres.`;
                    }
                    const matchThrottle = msg.match(/Too many .*? in (\d+) seconds\./i);
                    if (matchThrottle) {
                        return `Demasiados intentos. Por favor intenta de nuevo en ${matchThrottle[1]} segundos.`;
                    }
                    if (/^(The |This |We |Please |Too many |Your |passwords\.|validation\.)/.test(msg)) {
                        return 'No se pudo restablecer la contraseña. Verifica tus datos o solicita un nuevo enlace de recuperación.';
                    }

                    return msg;
                }

                const ul = document.createElement('ul');
                ul.style.textAlign = 'left';
                ul.style.paddingLeft = '20px';
                ul.style.listStyleType = 'disc';
                const mensajesUnicos = [...new Set(errores.map(traducirError))];
                mensajesUnicos.forEach(function (err) {
                    const li = document.createElement('li');
                    li.className = 'mb-1';
                    li.textContent = err;
                    ul.appendChild(li);
                });
                Swal.fire({
                    icon: 'warning',
                    title: 'Aviso',
                    html: ul,
                    confirmButtonColor: '#691C32',
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('resetForm');
            const passInput = document.getElementById('password');
            const passConfirmInput = document.getElementById('password_confirmation');
            function mostrarAlerta(titulo, texto, inputFocus) {
                Swal.fire({
                    icon: 'warning',
                    title: titulo,
                    text: texto,
                    confirmButtonColor: '#691C32',
                    confirmButtonText: 'Corregir'
                }).then(() => {
                    if (inputFocus) inputFocus.focus();
                });
            }

            form.addEventListener('submit', function (e) {
                const pass = passInput.value;
                const passConfirm = passConfirmInput.value;

                if (!pass) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Campo obligatorio',
                        'Por favor ingresa tu nueva contraseña.',
                        passInput
                    );
                    return;
                }

                if (/\s/.test(pass)) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Contraseña inválida',
                        'La contraseña no debe contener espacios en blanco.',
                        passInput
                    );
                    return;
                }

                const faltantes = [];
                if (pass.length < 8) faltantes.push('mínimo 8 caracteres');
                if (!/[A-Z]/.test(pass)) faltantes.push('al menos una letra mayúscula');
                if (!/[a-z]/.test(pass)) faltantes.push('al menos una letra minúscula');
                if (!/[0-9]/.test(pass)) faltantes.push('al menos un número');
                if (!/[^A-Za-z0-9\s]/.test(pass)) faltantes.push('al menos un signo (ej. - . _ ! @ # $)');

                if (faltantes.length > 0) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Contraseña insegura',
                        'Tu contraseña debe incluir: ' + faltantes.join(', ') + '. (Ejemplo: Ejempl0-894)',
                        passInput
                    );
                    return;
                }

                if (pass !== passConfirm) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Contraseñas no coinciden',
                        'Asegúrate de que ambas contraseñas escritas sean exactamente iguales.',
                        passConfirmInput
                    );
                    return;
                }
            });
        });
    </script>
</body>
</html>
