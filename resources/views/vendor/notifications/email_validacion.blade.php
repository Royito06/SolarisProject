<html lang="es">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Hola {{ $nombre . " " . $apellido_paterno . " " . $apellido_materno }}</h2> 
    <p>Gracias por iniciar el proceso de inscripción al Concurso Regional de Física y Matemáticas.</p>
    <p>Queremos asegurarnos que el correo que nos proporcionaste es correcto. Una vez confirmado tu correo, podrás acceder a la plataforma para completar tu solicitud de inscripción y/o actualizar tu perfil.</p>
    <p>Para verificar tu correo haz click en el siguiente enlace: 
    <a href="{{ redirect()->getUrlGenerator()->previous() . '/verify/' . $confirmation_code }}">
        confirmar mi correo</a>.<br>
    </p>
    <p>
    Para mayor información, puedes consultar la página del evento o comunicarte al correo concurso.fismat@unison.mx.
</p>
<p>
Atentamente<br>
Comité Organizador</p>

<!--
    <p>Por favor confirma tu correo electrónico haciendo click en el siguiente enlace:</p>
    <a href="{{ redirect()->getUrlGenerator()->previous() . '/verify/' . $confirmation_code }}">
        Quiero confirmar Mi Correo
    </a>
    <p>Una vez confirmado tu corrreo, podrás acceder al sistema para actualizar tu perfil y solicitar tu inscripción.</p>
    <p>Para mayor información del evento, puedes visitar <a href="https://concursofismat.uson.mx/">Concurso Regional</a> </p>
    <p>Para cualquier consulta o aclaración relacionada con el registro de inscripción comunicarse con concurso.fismat@unison.mx </p>
-->
</body>
</html>