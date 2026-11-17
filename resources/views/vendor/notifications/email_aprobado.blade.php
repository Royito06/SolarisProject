<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Confirmación de Inscripción - CRFyM</title>
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
                    Nos complace confirmarte que tu solicitud de inscripción al <strong>Concurso Regional de Física y Matemáticas</strong> ha sido <strong>REVISADA Y APROBADA</strong> exitosamente por el Comité Evaluador.
                </p>

                <!-- Tarjeta con Folio y Detalles -->
                <div style="background-color: #eafaf1; border: 1px solid #28a745; border-left: 6px solid #28a745; border-radius: 6px; padding: 15px 20px; margin: 20px 0;">
                    <p style="margin: 0; font-size: 14px; text-transform: uppercase; color: #155724; font-weight: bold;">Tu Folio Oficial de Concursante:</p>
                    <p style="margin: 5px 0 10px; font-size: 26px; font-weight: bold; color: #004990; letter-spacing: 1px;">
                        {{ $folio ?? 'Asignado' }}
                    </p>
                    <p style="margin: 0; font-size: 14px; color: #155724;">
                        <strong>Concurso(s) registrado(s):</strong> {{ $concursos ?: 'General' }}
                    </p>
                    <p style="margin: 5px 0 0; font-size: 14px; color: #155724;">
                        <strong>Estatus:</strong> <span style="background-color: #28a745; color: #ffffff; padding: 2px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;">INSCRITO</span>
                    </p>
                </div>

                <h3 style="color: #333333; font-size: 16px; margin-bottom: 8px;">Instrucciones Importantes:</h3>
                <ul style="font-size: 14px; padding-left: 20px; color: #555555;">
                    <li style="margin-bottom: 6px;"><strong>Conserva tu número de folio:</strong> Lo necesitarás para ubicar tu aula de examen y recibir tu credencial oficial del concurso.</li>
                    <li style="margin-bottom: 6px;">Mantente atento a tu correo electrónico y al portal oficial del concurso, donde se publicarán los horarios específicos y distribución de sedes.</li>
                    <li style="margin-bottom: 6px;">El día del evento deberás presentarte con una identificación con fotografía.</li>
                </ul>

                <p style="font-size: 15px; margin-top: 25px;">
                    ¡Te esperamos el día del evento y te deseamos el mayor de los éxitos en tu participación!
                </p>

                <hr style="border: none; border-top: 1px solid #eeeeee; margin: 25px 0;">

                <p style="font-size: 13px; color: #777777; margin: 0;">
                    Para cualquier duda o aclaración sobre tu inscripción, puedes comunicarte al correo: 
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
