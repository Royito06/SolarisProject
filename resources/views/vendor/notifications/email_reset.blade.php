
<html lang="es">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Hola {{ $nombre . " " . $apellido_paterno . " " . $apellido_materno }}</h2>
        <p>Para cambiar de contraseña simplemente debes hacer click en el siguiente enlace:</p>
        <a href="{{ redirect()->getUrlGenerator()->previous() . '/' . $token }}">
            Clic para cambiar tu contraseña
        </a>
        
    <p>Para mayor información del evento, puedes visitar <a href="https://concursofismat.uson.mx/">Concurso Regional</a> </p>
    <p>Para cualquier consulta o aclaración relacionada con el registro de inscripción comunicarse con concurso.fismat@unison.mx </p>
    </body>
    </html>
    