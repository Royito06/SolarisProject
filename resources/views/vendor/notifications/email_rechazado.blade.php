<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Solicitud de Inscripción con Observaciones - CRFyM</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 20px; background-color: #f4f6f9;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e1e4e8; border-radius: 8px; overflow: hidden;">
        <!-- Encabezado con franja azul institucional -->
        <tr>
            <td style="background-color: #004990; padding: 25px 20px; text-align: center; color: #ffffff;">
                <h1 style="margin: 0; font-size: 22px; font-weight: bold;">Concurso Regional de Física y Matemáticas</h1>
                <p style="margin: 5px 0 0; font-size: 14px; opacity: 0.9;">Departamento de Física y Departamento de Matemáticas - Universidad de Sonora</p>
            </td>
        </tr>

        <!-- Franja dorada -->
        <tr>
            <td style="background-color: #f7a11a; height: 6px; font-size: 0; line-height: 0;">&nbsp;</td>
        </tr>

        <!-- Contenido -->
        <tr>
            <td style="padding: 30px 25px;">
                <h2 style="color: #004990; margin-top: 0;">¡Hola, {{ $nombre . " " . $apellido_paterno . " " . $apellido_materno }}!</h2>

                <p style="font-size: 15px;">
                    El Comité Evaluador ha revisado la documentación que enviaste para tu solicitud de inscripción al <strong>Concurso Regional de Física y Matemáticas</strong>.
                </p>

                <p style="font-size: 15px;">
                    Se encontraron algunas observaciones en tus documentos que requieren ser corregidas antes de poder aprobar tu participación:
                </p>

                <!-- Tarjeta con Observaciones y Documentos Rechazados -->
                <div style="background-color: #fff3cd; border: 1px solid #ffeeba; border-left: 6px solid #ffc107; border-radius: 6px; padding: 15px 20px; margin: 20px 0;">
                    <p style="margin: 0; font-size: 14px; color: #856404; font-weight: bold;">
                        Documento(s) con observaciones:
                    </p>
                    <p style="margin: 5px 0 10px; font-size: 16px; font-weight: bold; color: #dc3545;">
                        {{ $doc_rechazado ?: 'Documentación incompleta' }}
                    </p>

                    <p style="margin: 10px 0 5px; font-size: 14px; color: #856404; font-weight: bold;">
                        Detalle / Motivo de la observación:
                    </p>
                    <div style="background: #ffffff; padding: 12px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 14px; color: #333333; white-space: pre-wrap;">{{ $comentario }}</div>
                </div>

                <h3 style="color: #333333; font-size: 16px; margin-bottom: 8px;">¿Qué debes hacer ahora?</h3>
                <ol style="font-size: 14px; padding-left: 20px; color: #555555;">
                    <li style="margin-bottom: 6px;">Prepara el archivo o imagen corregida conforme a las observaciones indicadas arriba.</li>
                    <li style="margin-bottom: 6px;">Ingresa a la plataforma con tu correo electrónico y tu contraseña.</li>
                    <li style="margin-bottom: 6px;">En tu perfil, da clic en el botón <strong>Editar</strong> para volver a subir los documentos corregidos.</li>
                </ol>

                <div style="text-align: center; margin: 25px 0;">
                    <a href="{{ url('/login') }}" style="display: inline-block; background-color: #004990; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 15px;">
                        Ingresar a la Plataforma y Reenviar
                    </a>
                </div>

                <hr style="border: none; border-top: 1px solid #eeeeee; margin: 25px 0;">

                <p style="font-size: 13px; color: #777777; margin: 0;">
                    Si tienes dudas sobre las observaciones o necesitas asistencia, escríbenos a: 
                    <a href="mailto:concurso.fismat@unison.mx" style="color: #004990; font-weight: bold;">concurso.fismat@unison.mx</a>.
                </p>
            </td>
        </tr>

        <!-- Pie de página -->
        <tr>
            <td style="background-color: #f8f9fa; border-top: 1px solid #eeeeee; padding: 15px 20px; text-align: center; font-size: 12px; color: #888888;">
                <p style="margin: 0;">Universidad de Sonora · Hermosillo, Sonora, México</p>
                <p style="margin: 4px 0 0;">Comité Organizador del Concurso Regional de Física y Matemáticas</p>
            </td>
        </tr>
    </table>
</body>
</html>
