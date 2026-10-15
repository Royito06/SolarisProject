@extends('layouts.app')

@section('content')

@if (session('notification'))
    <div class="alert alert-success" role="alert">
        {{ session()->get('notification') }}
    </div>
@endif

@if (session('notification_mal'))
    <div class="alert alert-danger" role="alert">
        {{ session()->get('notification_mal') }}
    </div>
@endif

<div class="container">
    <div style="font-size: 20px">
        {{-- Contenido --}}
<br>En esta plataforma podrás inscribirte al Concurso Regional de Física y Matemáticas, para esto, deberás seguir las siguientes instrucciones:<br><br>
<ol>
    <li>Crea una cuenta en la pestaña <b><a href="http://registro.mat.uson.mx:8000/register">REGISTRO</a></b> proporcionando tu nombre, tu dirección de correo electrónico y una contraseña.</li><br>
    <li>Ingresa a la bandeja de entrada de tu correo electrónico, busca el correo de verificación que te hemos enviado, y <b>confirma</b> que la dirección de correo que nos proporcionaste te pertenece. Revisa la carpeta de correo no deseado o SPAM, si no recibiste el correo en tu bandeja de entrada.</li><br>
    <li>Una vez confirmado tu correo, serás redirigido a esta plataforma. En la pestaña <b><a href="http://registro.mat.uson.mx:8000/login">ACCESO</a></b> podrás ingresar a tu perfil y completar la información y envío de documentos requeridos para la inscripción. Deberás tener a la mano tu CURP, acta de nacimiento, una constancia de estudios expedida por la escuela donde estudias, y una fotografía para credencial, todos estos en formato JPG, JPEG o PNG.<br></li><br>
    <li>Al enviar tu solicitud, el comité organizador verificará que tu documentación está completa y <b>te confirmará por correo electrónico si tu inscripción se completó satisfactoriamente.</b> En caso de que el comité te solicite que reenvíes nuevamente algún documento, deberás ingresar a tu cuenta en esta plataforma y realizar el reenvío correspondiente.</li><br>
</ol>
<p><b>IMPORTANTE</b>: Tienes hasta el <del>12 de mayo</del> 16 de mayo de 2024 para inscribirte al concurso, y recuerda que la inscripción estará finalizada hasta que el Comité así te lo confirme por correo, de modo que te pedimos estar atento al mismo. 
    <br /><b style="color:red;">¡No habrá inscripciones presenciales durante los días del concurso!</b></p>
<p>Para dudas o aclaraciones comunicarse al correo <b><a href="mailto:concurso.fismat@unison.mx">concurso.fismat@unison.mx</a></b> o a los teléfonos 6622592108 y 6622592109.</p>
<!-- version2022
        <h4>El proceso de inscripción será habilitada hasta el <b> 27 de abril de 2023</b>. </h4>
        <p>Para poder participar en el Concurso Regional de Física y Matemáticas deben cumplirse las siguientes fases:</p>
        <p>
        <ol>
            <li><P><b >REGISTRO</b>. Cada estudiante interesado en participar debe proporcionar sus datos (nombre completo, correo electrónico, y una clave para acceder posteriormente) en la página de registro. Al recibir tus datos, te enviaremos un "<i>enlace de confirmación</i>" por correo electrónico (Alerta: Es muy probable que este correo llegue a tu carpeta de "no deseados" o "spam"). <b>Debes confirmar tu correo!</b>. El correo debe llegar en pocos minutos. Nota: el correo es personal, por tanto, solo se permite un estudiante por cada correo electrónico.</P></li>
            <li><b>DOCUMENTACIÓN</b>. Ingresar al sistema con tus datos de acceso (NOTA: Tu acceso se habilita después finalizar la fase de REGISTRO). Una vez ingresado, debes enviar los documentos requeridos por cada concurso en cuestión (debes revisar la convocatoria de cada concurso).</li>

            <p>
            <li><B>VALIDACIÓN</B>. El comité organizador del Concurso valida la documentación enviada por cada alumno (debes ser paciente, ya que existen muchos participantes). Una vez revisados los documentos, se les envía un nuevo correo electrónico con el resultado de la revisión. este proceso puede durar varios días</li>
            <ol>
                <li><B>INSCRIPCIÓN</B>. Cuando el Comité Organizador resuelva <B>afirmativamente</B> la revisión de la documentación del alumno, <b>la inscripción está completada!</b></li>
                <li><b>ACTUALIZACION</b>. Cuando el Comité Organizador resuelva <B>negativamente</B> la revisión de la documentación del alumno, se envía correo electrónico solicitando la actualización de los documentos.
                </li>
            </ol>
            </p>

            <li><b>APLICACIÓN</b>. Presentarse el día del concurso (<b>3 de mayo de 2023</b>) con una <b>identificación personal</b> para recoger su credencial de concursante, ya que sin ella, no podrás realizar el examen. El personal de la Universidad te guiará al lugar donde se realiza cada concurso.</li>
        </ol>
</p>
-->
    </div>
</div>

@endsection