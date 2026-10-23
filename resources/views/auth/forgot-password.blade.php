<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña | CONABIO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

        <!-- PANEL DERECHO: FORMULARIO -->
        <div class="lg:w-8/12 xl:w-[65%] flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-white">
            <div class="w-full max-w-md space-y-6">

                <div>
                    <div class="w-12 h-12 rounded-xl bg-vino-50 border border-vino-200 flex items-center justify-center text-vino-800 mb-4 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                        ¿Olvidaste tu contraseña?
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        Ingresa el correo electrónico institucional asociado a tu cuenta y te enviaremos un enlace seguro para restablecerla.
                    </p>
                </div>

                @if (session('status'))
                    <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <!-- Campo Correo (name="correo") -->
                    <div>
                        <label for="correo" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Correo institucional
                        </label>
                        <div class="relative rounded-lg">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </div>
                            <input id="correo" type="email" name="correo" value="{{ old('correo') }}"
                                placeholder="tu_usuario@conabio.gob.mx" required autofocus autocomplete="email"
                                class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-vino-800 focus:border-vino-800 transition outline-none" />
                        </div>
                        <x-input-error :messages="$errors->get('correo')" class="mt-1 text-xs text-red-600" />
                    </div>

                    <div class="pt-1">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-lg bg-vino-800 hover:bg-vino-900 active:bg-vino-950 text-white font-bold text-sm shadow-md hover:shadow-lg transition duration-150 cursor-pointer">
                            <span>Enviar enlace de recuperación</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="pt-4 border-t border-gray-100 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-vino-800 hover:text-vino-950 hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Volver al inicio de sesión</span>
                    </a>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
