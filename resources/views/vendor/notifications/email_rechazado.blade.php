<html lang="es">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Hola {{ $nombre . " " . $apellido_paterno . " " . $apellido_materno }}</h2>
    <p>Después de revisar los documentos enviados en tu solicitud de inscripción al Concurso Regional de Física y Matemáticas, detectamos los siguientes problemas:</p>
    <p>Documentos: <b>{{ $doc_rechazado }}</b>.</p>
    <p><pre>{{ $comentario }}</pre></p>
    <p><br>Por el momento tu solicitud ha sido rechazada, pero te invitamos a atender las observaciones anteriores y reenviar la documentación necesaria, ingresando a la plataforma de inscripciones con tu correo electrónico y contraseña.</p>
    <p>Para mayor información, puedes comunicarte al correo concurso.fismat@unison.mx.</p>
    <p>Atentamente<br>Comité Organizador</p>

    <!-- 
    <p>Después de revisar tus documentos para el Concurso, hemos encontrado los siguientes problemas:</p>
    <p>Documentos: <b>{{ $doc_rechazado }}</b>.</p>
    <p><pre>{{ $comentario }}</pre></p>
    <p>Por el momento tu estado dentro de nuestro registro se encuentra como <b>{{ $estado }}</b>.</p>
    <p>Si deseas subir tus archivos nuevamente, puedes ingresar al sistema (<a href="registro.mat.uson.mx"> Sistema de Inscripciones</a>) y actualiza tu perfil.</p>

    <p>Para mayor información del evento, puedes visitar <a href="https://concursofismat.uson.mx/">Concurso Regional</a> </p>
    <p>Para cualquier consulta o aclaración relacionada con el registro de inscripción comunicarse con concurso.fismat@unison.mx </p>
-->

</body>
</html>