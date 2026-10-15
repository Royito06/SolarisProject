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
            <li>Una vez confirmado tu correo, serás redirigido a esta plataforma. En la pestaña <b><a href="http://registro.mat.uson.mx:8000/login">ACCESO</a></b> podrás ingresar a tu perfil y completar la información y envío de documentos requeridos para la inscripción. Deberás tener a la mano tu CURP, una constancia de estudios (o credencial escolar) expedida por la escuela donde estudias, y una fotografía para credencial, todos estos en formato JPG, JPEG o PNG.<br></li><br>
            <li>Al enviar tu solicitud, el comité organizador verificará que tu documentación está completa y <b>te confirmará por correo electrónico si tu inscripción se completó satisfactoriamente.</b> En caso de que el comité te solicite que reenvíes nuevamente algún documento, deberás ingresar a tu cuenta en esta plataforma y realizar el reenvío correspondiente.</li><br>
        </ol>
        
        <p><b>IMPORTANTE</b>: Tienes hasta el <strong>1 de mayo de 2026</strong> para inscribirte al concurso, y recuerda que la inscripción estará finalizada hasta que el Comité así te lo confirme por correo, de modo que te pedimos estar atento al mismo. 
        <br /><b style="color:red;">¡No habrá inscripciones presenciales durante los días del concurso!</b></p>
        <p>Para dudas o aclaraciones comunicarse al correo <b><a href="mailto:concurso.fismat@unison.mx">concurso.fismat@unison.mx</a></b> o a los teléfonos 6622592108 y 6622592109.</p>
    </div>
</div>

@endsection