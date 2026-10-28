@extends('layouts.app_admin')

@section('content')

@php
    //dd($validacion);
@endphp

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0 text-dark">Validacion del asistente {{$validacion->nombre}}</h1>
                <hr style="background-color: black;">
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid ">

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Informacion del Asistente</h3>
            </div>
    
            @if(Auth::guard('revisor')->user()->rol !== 'Visualizador')
            <form role="form" method="POST" action="{{route('validaciones.update', $validacion->id)}}">
                @csrf
                @method("PUT")
                <div class="card-body">
                    <div class="form-group" style="font-size: 20px">
                        <label class="col-md-2 col-form-label text-md-left" for="">Nombre: </label>
                        <label for="">{{$validacion->nombre.' '.$validacion->apellido_paterno.' '.$validacion->apellido_materno}}</label>
                    </div>

                    <div class="form-group" style="font-size: 20px">
                        <label class="col-md-2 col-form-label text-md-left" for="">Fecha de nacimiento:</label>
                        <label for="">{{$validacion->fecha_nacimiento}}</label>
                    </div>

                    <div class="form-group" style="font-size: 20px">
                        <label class="col-md-2 col-form-label text-md-left" for="">CURP: </label>
                        <label for="">{{$validacion->curp}}</label>
                    </div>

                    <div class="form-group" style="font-size: 20px">
                        <label class="col-md-2 col-form-label text-md-left" for="">Escuela de origen:</label>
                        <label for="">{{$validacion->escuela_origen}}</label>
                    </div>

                    <hr>


                    @if (session('jsAlert'))
                        <div class="alert alert-danger" role="alert">
                            {{ session()->get('jsAlert') }}
                        </div>
                    @endif

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Imagenes del Asistente</h3>
                        </div>
                        <div class="form-group" style="font-size: 20px">
                            <label class="col-md-2 col-form-label text-md-left" for="">Foto para la credencial:</label>
                            <img src="/storage/{{$validacion->foto_asistente}}" alt="">
    
                            <p>
                                <b style="color: green"><input type="radio" name="fotoRechazo" value="" onchange="sholo('#textFoto','esconder', 'txt1')" checked/>&nbsp;Aceptar&nbsp;&nbsp;&nbsp;</b>
                                <b style="color: red"><input type="radio" name="fotoRechazo" value="Foto identificatoria" onchange="sholo('#textFoto','mostrar', 'txt1')"/>&nbsp;Rechazar</b>
                            </p>
    
                            <div class="form-group" style="font-size: 20px; display:none;" id="textFoto">
                                <textarea style="width: 100%" class="@error('textoFotoRechazo') is-invalid @enderror" name="textoFotoRechazo" id="txt1" cols="" rows="3" minlength="10" placeholder="Introduzca un breve texto sobre la razón de rechazo">{{ old('textoFotoRechazo') }}</textarea>
                                @error('textoFotoRechazo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
    
                        <hr>
   
                        <div class="form-group" style="font-size: 20px">
                            <label class="col-md-2 col-form-label text-md-left" for="">Constancia o credencial escolar:</label>
                            <img src="/storage/{{$validacion->constancia_escuela}}" alt="">
    
                            <p>
                                <b style="color: green"><input type="radio" name="constanciaRechazo" value="" onchange="sholo('#textConstancia','esconder', 'txt3')" checked/>&nbsp;Aceptar&nbsp;&nbsp;&nbsp;</b>
                                <b style="color: red"><input type="radio" name="constanciaRechazo" value="Constancia escolar" onchange="sholo('#textConstancia','mostrar', 'txt3')"/>&nbsp;Rechazar</b>
                            </b>
    
                            <div class="form-group" style="font-size: 20px; display: none;" id="textConstancia">
                                <textarea style="width: 100%" class="@error('textoConstanciaRechazo') is-invalid @enderror" name="textoConstanciaRechazo" id="txt3" cols="" rows="3" minlength="10" placeholder="Introduzca un breve texto sobre la razón de rechazo">{{ old('textoConstanciaRechazo') }}</textarea>
                                @error('textoConstanciaRechazo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
        
                    </div>
                </div>
    
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" aria-required="true">Validar</button>
                    <a type="button" href="{{route('revisores.lista-espera')}}" class="btn btn-secondary" aria-required="true">Cancelar</a>
                </div>
            </form>      
            @else
                <div class="alert alert-warning mt-3"><i class="fas fa-eye mr-1"></i><strong>Modo lectura:</strong> Tu rol de Visualizador no permite aprobar ni rechazar asistentes.</div>
            @endif
        </div>
    </div>
</div>

<script type="text/javascript">
    function sholo(e, e2, e3) {
        if(e2 =="esconder"){
            $(e).hide();
            //document.getElementById(e3).value = "";
        } else if (e2 == "mostrar") {
            $(e).show();
            //document.getElementById(e3).value = "Introduzca un breve texto sobre la razón de rechazo";
        } 
    };
</script>

@endsection
