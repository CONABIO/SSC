<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SISECAP</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_conabio_transparente_bolita.png') }}?v=1">
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
            <img src="https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?q=80&w=1200&auto=format&fit=crop" alt="Naturaleza Mexicana" class="absolute inset-0 w-full h-full object-cover opacity-10 mix-blend-luminosity pointer-events-none" />
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-dorado-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 w-full py-4">
                <div class="flex flex-col items-center justify-center mb-8">
                    <img src="{{ asset('images/logo_conabio_transparente.png') }}" alt="Logo CONABIO" class="h-32 sm:h-36 w-auto mx-auto block object-contain brightness-0 invert drop-shadow-xl transition-all" />
                </div>
                <div class="pt-8 border-t-2 border-dorado-500/30 text-center sm:text-left">
                    <h1 class="text-3xl sm:text-4xl lg:text-4xl font-black text-white leading-tight tracking-tight mt-12"> Sistema de Seguimiento de <span class="text-dorado-400">Capacitación</span></h1>
                    <p class="text-sm sm:text-base text-rose-100/90 mt-4 leading-relaxed font-normal">Control de Profesionalización de las Personas Servidoras Públicas asignadas a la Comisión Nacional para el Conocimiento y Uso de la Biodiversidad.</p>
                </div>
            </div>
            <div class="relative z-10 text-[11px] text-rose-200/60 border-t border-vino-800/80 pt-4 mt-6">
                © {{ date('Y') }} CONABIO • Comisión Nacional para el Conocimiento y Uso de la Biodiversidad.
            </div>
        </div>

        <div class="lg:w-8/12 xl:w-[65%] flex items-center justify-center p-6 sm:p-10 lg:p-12 bg-white">
            <div class="w-full max-w-lg space-y-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                        Crear una cuenta
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Ingresa tus datos para registrarte como colaborador en el sistema.
                    </p>
                </div>
                <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-3.5" novalidate>
                    @csrf
                    <div>
                        <label for="idEmpleado" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                            Número de Empleado
                        </label>
                        <input id="idEmpleado" type="text" inputmode="numeric" name="idEmpleado" value="{{ old('idEmpleado') }}"
                        maxlength="6" placeholder="Ej. 1045" required autofocus
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);"
                        class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                    </div>

                    <div>
                        <label for="nombre" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                            Nombre(s)
                        </label>
                        <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}"
                            maxlength="50" placeholder="Ej. Juan" required autocomplete="given-name"
                            class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="apellidoP" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                Primer Apellido
                            </label>
                            <input id="apellidoP" type="text" name="apellidoP" value="{{ old('apellidoP') }}"
                                maxlength="50" placeholder="Ej. Perez" required autocomplete="family-name"
                                class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                        </div>

                        <div>
                            <label for="apellidoM" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                Segundo Apellido
                            </label>
                            <input id="apellidoM" type="text" name="apellidoM" value="{{ old('apellidoM') }}"
                                maxlength="50" placeholder="Ej. Perez" autocomplete="additional-name"
                                class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                        </div>
                    </div>

                    <div>
                        <label for="correo" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                            Correo institucional
                        </label>
                        <input id="correo" type="email" name="correo" value="{{ old('correo') }}"
                            maxlength="100" placeholder="ejemplo@semarnat.gob.mx" required autocomplete="email"
                            pattern="[A-Za-z0-9._%+-]+@semarnat\.gob\.mx" title="Usa un correo con dominio @semarnat.gob.mx"
                            class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                    </div>

                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="contrasena" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                    Contraseña
                                </label>
                                <input id="contrasena" type="password" name="contrasena"
                                    minlength="8" maxlength="255" placeholder="••••••••••••" required
                                    autocomplete="new-password"
                                    class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                            </div>

                            <div>
                                <label for="contrasena_confirmation" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                    Confirmar Contraseña
                                </label>
                                <input id="contrasena_confirmation" type="password" name="contrasena_confirmation"
                                    minlength="8" maxlength="255" placeholder="Repite contraseña" required
                                    autocomplete="new-password"
                                    class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                            </div>
                        </div>
                        <p class="mt-1.5 text-[11px] text-gray-500">
                            Debe tener mínimo 8 caracteres e incluir letras mayúsculas, minúsculas, números y signos (Ej. <span class="font-semibold text-gray-700">Ejempl0-894</span>).
                        </p>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-lg bg-vino-800 hover:bg-vino-900 active:bg-vino-950 text-white font-bold text-sm shadow-md hover:shadow-lg transition duration-150 cursor-pointer">
                            <span>Registrarme en la Plataforma</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="pt-3 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500">
                        ¿Ya tienes una cuenta registrada?
                        <a href="{{ route('login') }}" class="font-bold text-vino-800 hover:text-vino-950 hover:underline ml-1">
                            Inicia sesión aquí
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const errores = @json($errors->all());
                const ul = document.createElement('ul');
                ul.style.textAlign = 'left';
                ul.style.paddingLeft = '20px';
                ul.style.listStyleType = 'disc';

                errores.forEach(function (err) {
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
            const form = document.getElementById('registerForm');
            const idEmpleadoInput = document.getElementById('idEmpleado');
            const nombreInput = document.getElementById('nombre');
            const apellidoPInput = document.getElementById('apellidoP');
            const apellidoMInput = document.getElementById('apellidoM');
            const correoInput = document.getElementById('correo');
            const passInput = document.getElementById('contrasena');
            const passConfirmInput = document.getElementById('contrasena_confirmation');

            function esCorreoValido(correo) {
                return /^[a-zA-Z0-9._%+-]+@semarnat\.gob\.mx$/i.test(correo.trim());
            }

            function tieneEspaciosInvalidos(texto) {
                const limpio = texto.trim();
                const totalEspacios = (limpio.match(/\s/g) || []).length;
                const espaciosSeguidos = /\s{2,}/.test(limpio);
                return totalEspacios > 2 || espaciosSeguidos;
            }

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

            [nombreInput, apellidoPInput, apellidoMInput].forEach((input) => {
                input.addEventListener('input', function () {
                    this.value = this.value.replace(/\s{2,}/g, ' ');
                });
            });

            form.addEventListener('submit', function (e) {
                const idRaw = idEmpleadoInput.value.trim();
                const idNum = Number(idRaw);
                const nombre = nombreInput.value.trim();
                const apellidoP = apellidoPInput.value.trim();
                const apellidoM = apellidoMInput.value.trim();
                const correo = correoInput.value.trim();
                const pass = passInput.value;
                const passConfirm = passConfirmInput.value;
                if (idEmpleadoInput.validity.badInput || !/^\d+$/.test(idRaw) || idNum < 1 || idNum > 1000000000) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Número de empleado inválido',
                        'Ingresa un número entero válido entre 1 y 1000000000.',
                        idEmpleadoInput
                    );
                    return;
                }

                if (nombre === '' || nombre.length > 50) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Nombre inválido',
                        nombre === '' ? 'El campo Nombre(s) es obligatorio.' : 'El nombre no puede superar los 50 caracteres.',
                        nombreInput
                    );
                    return;
                }
                if (tieneEspaciosInvalidos(nombre)) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Espacios inválidos en Nombre(s)',
                        'El nombre no puede contener espacios seguidos ni más de 2 espacios en blanco.',
                        nombreInput
                    );
                    return;
                }

                if (apellidoP === '' || apellidoP.length > 50) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Primer apellido inválido',
                        apellidoP === '' ? 'El campo Primer Apellido es obligatorio.' : 'El primer apellido no puede superar los 50 caracteres.',
                        apellidoPInput
                    );
                    return;
                }
                if (tieneEspaciosInvalidos(apellidoP)) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Espacios inválidos en Primer Apellido',
                        'El primer apellido no puede contener espacios seguidos ni más de 2 espacios en blanco.',
                        apellidoPInput
                    );
                    return;
                }

                if (apellidoM.length > 50) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Segundo apellido demasiado largo',
                        'El segundo apellido no puede superar los 50 caracteres.',
                        apellidoMInput
                    );
                    return;
                }
                if (apellidoM !== '' && tieneEspaciosInvalidos(apellidoM)) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Espacios inválidos en Segundo Apellido',
                        'El segundo apellido no puede contener espacios seguidos ni más de 2 espacios en blanco.',
                        apellidoMInput
                    );
                    return;
                }

                if (!esCorreoValido(correo) || correo.length > 100) {
                    e.preventDefault();
                    mostrarAlerta(
                        'Correo no permitido',
                        correo.length > 100
                            ? 'El correo no puede superar los 100 caracteres.'
                            : 'Solo se permiten correos institucionales con terminación @semarnat.gob.mx',
                        correoInput
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
