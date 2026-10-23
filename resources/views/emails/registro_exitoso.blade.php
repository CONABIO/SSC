<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a la Plataforma</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: 'Segoe UI', Helvetica, Arial, sans-serif; color: #1f2937;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f5; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table width="100%" max-width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.06);">
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
                            <h2 style="color: #111827; font-size: 18px; margin-top: 0;">
                                ¡Hola, {{ $empleado->nombre }} {{ $empleado->apellidoP }}!
                            </h2>

                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">
                                Tu cuenta institucional ha sido registrada exitosamente en la plataforma de seguimiento de cursos y acreditaciones de CONABIO.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; margin: 25px 0; padding: 15px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 8px; font-size: 13px; color: #6b7280;">
                                            <strong>Usuario / Correo:</strong> <span style="color: #111827;">{{ $empleado->correo }}</span>
                                        </p>
                                        <p style="margin: 0; font-size: 13px; color: #6b7280;">
                                            <strong>Estado:</strong> <span style="color: #059669; font-weight: bold;">Activo</span>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">
                                A partir de este momento puedes ingresar al sistema para consultar tu catálogo de <strong>cursos obligatorios</strong> normativos y solicitar acreditación en <strong>cursos optativos</strong> de especialización técnica.
                            </p>

                            <div style="text-align: center; margin: 35px 0 10px;">
                                <a href="{{ route('login') }}" style="background-color: #691C32; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: bold; font-size: 14px; display: inline-block;">
                                    Ingresar al Sistema
                                </a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb; font-size: 11px; color: #9ca3af;">
                            <p style="margin: 0;">
                                Comisión Nacional para el Conocimiento y Uso de la Biodiversidad.
                            </p>
                            <p style="margin: 5px 0 0;">
                                Este es un correo automático generado por el sistema, por favor no respondas a este mensaje.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
