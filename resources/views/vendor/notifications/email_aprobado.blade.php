<html lang="es">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Hola {{ $nombre . " " . $apellido_paterno . " " . $apellido_materno }}</h2>

    <p>Te confirmamos que tu inscripción al LV Concurso Regional de Física y Matemáticas ha finalizado exitosamente.</p>
    <p>Es importante que conserves el número de folio que se te ha asignado, que puedes consultar en la plataforma de registro, pues te servirá para identificar el aula donde se aplicarán los exámenes.</p>
    <p>Te solicitamos estar atento a la página del evento donde se publicarán los horarios correspondientes.</p>
    <p><br>¡Te esperamos el día del evento y te deseamos éxito!</p>
    <p>Para mayor información, puedes comunicarte al correo concurso.fismat@unison.mx.</p>
    <p>Atentamente<br>
        Comité Organizador 
    </p>
<!--
    <h3>¡Gracias por Inscribirte en el <strong>Concurso Regional de Física y Matemáticas</strong> !
    <p>Actualmente, ya estás INSCRITO en el concurso (<b>{{ $estado }}</b>).</p>
    <p>Los concursos en los cuales podrás participar son: <b>{{ $concursos }}</b></p>
    <p>¡Te esperamos el día del Evento para hacer entrega de tu Credencial!</p>
    
    <p>Para mayor información del evento, puedes visitar <a href="https://concursofismat.uson.mx/">Concurso Regional</a> </p>
    <p>Para cualquier consulta o aclaración relacionada con el registro de inscripción comunicarse con concurso.fismat@unison.mx </p>
-->
</body>
</html>