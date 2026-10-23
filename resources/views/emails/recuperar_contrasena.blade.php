<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: 'Segoe UI', Helvetica, Arial, sans-serif; color: #1f2937;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f5; padding: 40px 10px;">
        <tr>
            <td align="center">

                <!-- Contenedor del correo -->
                <table width="100%" max-width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.06);">

                    <!-- Encabezado Vino Institucional con Logo -->
                    <tr>
                        <td style="background-color: #691C32; padding: 25px 20px; text-align: center;">
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

                    <!-- Cuerpo del Mensaje -->
                    <tr>
                        <td style="padding: 35px 30px;">
                            <h2 style="color: #111827; font-size: 18px; margin-top: 0;">
                                ¡Hola, {{ $empleado->nombre }} {{ $empleado->apellidoP }}!
                            </h2>

                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">
                                Recibimos una solicitud para restablecer la contraseña de tu cuenta en la plataforma de capacitación de CONABIO.
                            </p>

                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">
                                Para ingresar una nueva contraseña, haz clic en el siguiente botón:
                            </p>

                            <!-- Botón de Restablecimiento -->
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $url }}" style="background-color: #691C32; color: #ffffff; text-decoration: none; padding: 13px 30px; border-radius: 6px; font-weight: bold; font-size: 14px; display: inline-block;">
                                    Restablecer mi Contraseña
                                </a>
                            </div>

                            <!-- Aviso de vigencia -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fffbeb; border-radius: 8px; border: 1px solid #fef3c7; margin: 20px 0; padding: 12px 15px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0; font-size: 12px; color: #92400e; line-height: 1.5;">
                                            ⏱️ <strong>Nota de seguridad:</strong> Este enlace de recuperación tiene una vigencia de <strong>60 minutos</strong>. Si expira, deberás solicitar uno nuevo.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #6b7280; font-size: 13px; line-height: 1.5; margin-bottom: 0;">
                                Si tú no realizaste esta solicitud, puedes ignorar este correo de forma segura; tu contraseña actual no será modificada.
                            </p>
                        </td>
                    </tr>

                    <!-- Pie de página -->
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
