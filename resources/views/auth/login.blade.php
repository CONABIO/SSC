<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plataforma de Capacitación | CONABIO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif']
                    },
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
        <div
            class="lg:w-4/12 xl:w-[35%] bg-gradient-to-b from-vino-900 via-vino-850 to-vino-950 text-white flex flex-col justify-between p-7 sm:p-9 relative overflow-hidden border-r border-vino-700/40">
            <img src="https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?q=80&w=1200&auto=format&fit=crop"
                alt="Naturaleza Mexicana"
                class="absolute inset-0 w-full h-full object-cover opacity-10 mix-blend-luminosity pointer-events-none" />
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-dorado-500/15 rounded-full blur-3xl pointer-events-none">
            </div>
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
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                        Iniciar sesión
                    </h2>
                    <p class="mt-1.5 text-sm text-gray-500">
                        Ingresa con tu cuenta institucional para consultar y registrar tus capacitaciones.
                    </p>
                </div>
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="correo"
                            class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Correo institucional
                        </label>
                        <div class="relative rounded-lg">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </div>
                            <input id="correo" type="email" name="correo" value="{{ old('correo') }}"
                                placeholder="tu_usuario@semarnat.gob.mx" required autofocus autocomplete="username"
                                class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                        </div>
                        <x-input-error :messages="$errors->get('correo')" class="mt-1 text-xs text-red-600" />
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="contrasena"
                                class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Contraseña
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-xs text-vino-800 hover:text-vino-950 font-semibold hover:underline">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>
                        <div class="relative rounded-lg">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="contrasena" type="password" name="contrasena" placeholder="••••••••••••" required
                                autocomplete="current-password"
                                class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                        </div>
                        <x-input-error :messages="$errors->get('contrasena')" class="mt-1 text-xs text-red-600" />
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="h-4 w-4 rounded border-gray-300 text-vino-800 focus:ring-vino-800" />
                            <span class="ml-2 text-xs text-gray-600 select-none">Recordar mis credenciales</span>
                        </label>
                    </div>
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-lg bg-vino-800 hover:bg-vino-900 active:bg-vino-950 text-white font-bold text-sm shadow-md hover:shadow-lg transition duration-150 cursor-pointer">
                        <span>Ingresar a la Plataforma</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                @if (Route::has('register'))
                    <div class="pt-4 border-t border-gray-100 text-center">
                        <p class="text-xs text-gray-500">
                            ¿Aún no estás registrado en el sistema?
                            <a href="{{ route('register') }}" class="font-bold text-vino-800 hover:text-vino-950 hover:underline ml-1">
                                Regístrate aquí
                            </a>
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>

</body>

</html>
