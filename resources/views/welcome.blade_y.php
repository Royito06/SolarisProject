<!--
    welcome.blade.php : Archivo de cierre de 2024.
    Para activar, habilitar el archivo "welcome.blade" de otra edicion y poner a este cierre en "stand by".
-->
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
        <br><h4>INSCRIPCIONES CERRADAS!</h4>
        <br></b>Recuerda que la inscripción estará finalizada hasta que el comité así te lo confirme por correo, de modo que te pedimos estar atento al mismo. 
<!--        <br><b style="color:red;">¡No habrá inscripciones presenciales durante los días del concurso!</b></p> -->
        <p>Para dudas o aclaraciones comunicarse al correo <b><a href="mailto:concurso.fismat@unison.mx">concurso.fismat@unison.mx</a></b> o a los teléfonos 6622592108 y 6622592109.</p>
    </div>
</div>

@endsection