<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Cuenta | CONABIO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
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

        <!-- PANEL IZQUIERDO INSTITUCIONAL -->
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
                        Control, registro y acreditación curricular del personal de la Comisión Nacional para el
                        Conocimiento y Uso de la Biodiversidad.
                    </p>
                </div>
            </div>

            <div class="relative z-10 text-[11px] text-rose-200/60 border-t border-vino-800/80 pt-4 mt-6">
                © {{ date('Y') }} CONABIO • Comisión Nacional para el Conocimiento y Uso de la Biodiversidad.
            </div>
        </div>

        <!-- PANEL DERECHO: FORMULARIO DE REGISTRO -->
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

                <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-3.5">
                    @csrf

                    <!-- 1. Número de Empleado (idEmpleado) -->
                    <div>
                        <label for="idEmpleado" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                            Número de Empleado
                        </label>
                        <input id="idEmpleado" type="number" name="idEmpleado" value="{{ old('idEmpleado') }}"
                            placeholder="Ej. 1045" required autofocus
                            class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                    </div>

                    <div>
                        <label for="nombre" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                            Nombre(s)
                        </label>
                        <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}"
                            placeholder="Ej. Juan" required autocomplete="given-name"
                            class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="apellidoP" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                Primer Apellido
                            </label>
                            <input id="apellidoP" type="text" name="apellidoP" value="{{ old('apellidoP') }}"
                                placeholder="Ej. Perez" required autocomplete="family-name"
                                class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                        </div>

                        <div>
                            <label for="apellidoM" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                Segundo Apellido
                            </label>
                            <input id="apellidoM" type="text" name="apellidoM" value="{{ old('apellidoM') }}"
                                placeholder="Ej. Perez" autocomplete="additional-name"
                                class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                        </div>
                    </div>

                    <!-- Campo Correo -->
                    <div>
                        <label for="correo" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                            Correo institucional
                        </label>
                        <input id="correo" type="email" name="correo" value="{{ old('correo') }}"
                            placeholder="ejemplo@conabio.gob.mx" required autocomplete="email"
                            class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="contrasena" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                Contraseña
                            </label>
                            <input id="contrasena" type="password" name="contrasena" placeholder="••••••••••••" required
                                autocomplete="new-password"
                                class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                        </div>

                        <div>
                            <label for="contrasena_confirmation" class="block text-xs font-bold tracking-wider text-gray-700 mb-1">
                                Confirmar Contraseña
                            </label>
                            <input id="contrasena_confirmation" type="password" name="contrasena_confirmation"
                                placeholder="Repite contraseña" required autocomplete="new-password"
                                class="block w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                        </div>
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
                let listaErrores = '';
                @foreach ($errors->all() as $error)
                    listaErrores += '<li class="mb-1">{{ $error }}</li>';
                @endforeach

                Swal.fire({
                    icon: 'warning',
                    title: 'Aviso',
                    html: '<ul style="text-align: left; padding-left: 20px; list-style-type: disc;">' + listaErrores + '</ul>',
                    confirmButtonColor: '#691C32',
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('registerForm');
            const correoInput = document.getElementById('correo');
            const passInput = document.getElementById('contrasena');
            const passConfirmInput = document.getElementById('contrasena_confirmation');

            function esCorreoValido(correo) {
                return /^[a-zA-Z0-9._%+-]+@conabio\.gob\.mx$/i.test(correo.trim());
            }
            form.addEventListener('submit', function (e) {
                const correo = correoInput.value.trim();
                const pass = passInput.value;
                const passConfirm = passConfirmInput.value;
                if (!esCorreoValido(correo)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Correo no permitido',
                        text: 'Solo se permiten correos institucionales con dominio @conabio.gob.mx',
                        confirmButtonColor: '#691C32',
                        confirmButtonText: 'Corregir'
                    }).then(() => correoInput.focus());
                    return;
                }
                if (pass !== passConfirm) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Contraseñas no coinciden',
                        text: 'Asegúrate de que ambas contraseñas escritas sean exactamente iguales.',
                        confirmButtonColor: '#691C32',
                        confirmButtonText: 'Corregir'
                    }).then(() => passConfirmInput.focus());
                    return;
                }
            });
        });
    </script>

</body>
</html>
