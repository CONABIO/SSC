<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recordatorio de Capacitación</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: 'Segoe UI', Helvetica, Arial, sans-serif; color: #1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f5; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="background-color: #691C32; padding: 30px; text-align: center;">
                            <img
                                src="{{ $message->embed(public_path('images/logo_conabio_transparente.png')) }}"
                                alt="Logo CONABIO"
                                style="height: 55px; width: auto; max-width: 220px; display: block; margin: 0 auto 12px auto; border: 0;"
                            />
                            <p style="color: #D4C19C; margin: 0; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">
                                Sistema de Seguimiento de Capacitación
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 35px 30px;">
                            <h2 style="color: #111827; font-size: 19px; margin-top: 0; font-weight: 800;">
                                ¡Hola, {{ $empleado->nombre }} {{ $empleado->apellidoP }}!
                            </h2>
                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                                Te recordamos que el <strong>periodo normativo de capacitación institucional</strong> se encuentra activo. A continuación, te presentamos el estado actual de tu avance:
                            </p>
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fdf2f4; border-radius: 10px; border-left: 4px solid #691C32; margin-bottom: 25px; padding: 18px;">
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="font-size: 13px; color: #6b7280; padding-bottom: 6px;">
                                                    <strong>Horas acumuladas:</strong>
                                                </td>
                                                <td align="right" style="font-size: 14px; font-weight: 800; color: #691C32;">
                                                    {{ $totalHoras }} hrs de 40 hrs
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 13px; color: #6b7280;">
                                                    <strong>Estatus de la meta:</strong>
                                                </td>
                                                <td align="right" style="font-size: 12px; font-weight: bold; color: {{ $totalHoras >= 40 ? '#059669' : '#d97706' }};">
                                                    {{ $totalHoras >= 40 ? 'Meta Cumplida' : 'Pendiente' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <h3 style="color: #374151; font-size: 14px; margin: 0 0 10px 0; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                Cursos obligatorios pendientes por acreditar:
                            </h3>
                            @if($faltantes->count() > 0)
                                <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; margin-bottom: 25px; padding: 15px;">
                                    @foreach($faltantes as $curso)
                                        <tr>
                                            <td style="padding: 6px 0; font-size: 13px; color: #92400e; border-bottom: 1px solid #fef3c7;">
                                                <span style="font-weight: bold;">• {{ $curso->nombre }}</span>
                                            </td>
                                            <td align="right" style="padding: 6px 0; font-size: 12px; font-weight: bold; color: #b45309; border-bottom: 1px solid #fef3c7; white-space: nowrap;">
                                                ⏱️ {{ $curso->horas }} hrs
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 15px; margin-bottom: 25px; text-align: center; color: #065f46; font-size: 13px; font-weight: bold;">
                                    ✓ ¡Felicidades! Tienes todos tus cursos obligatorios acreditados.
                                </div>
                            @endif
                            <p style="color: #4b5563; font-size: 13px; line-height: 1.6;">
                                Por favor ingresa a la plataforma para registrar tus constancias oficiales y dar cumplimiento a los lineamientos normativos.
                            </p>
                            <div style="text-align: center; margin: 35px 0 10px;">
                                <a href="{{ route('login') }}" style="background-color: #691C32; color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: bold; font-size: 14px; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                                    Ingresar a la Plataforma
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</bod
