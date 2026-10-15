@extends('layouts.app')

@section('content')
@php
    //dd(Auth::user()->documentos->validaciones()->latest()->first());
    //dd(Auth::user()->concursos()->get()->all());
@endphp
    <div class="container">
        <div class="justify-content-center">
            <!--
            <div class="container">
                <div class="card-header h-secundario">{{ __('Información de asistente') }}</div>
            </div>
        -->
            <div class="card-body" style="overflow: hidden;">
                <!--<img style="height: 256px; float: left; " src="/img/prueba.jpg" alt="">-->
                <div style="font-size: 20px">
                    <label for=""><b>Estatus de la solicitud de inscripción</b></label>
                    @if (Auth::user()->documentos)
                        @if (Auth::user()->documentos->validaciones()->latest()->first()->estado == "Rechazado")
                            <span class="badge badge-danger">Rechazado</span>
                        @endif
                        @if (Auth::user()->documentos->validaciones()->latest()->first()->estado == "Espera")
                            <span class="badge badge-secondary">En revisión</span>
                        @endif
                        @if (Auth::user()->documentos->validaciones()->latest()->first()->estado == "Aprobado")
                            <span class="badge badge-success">Inscrito</span>
                        @endif
                    @else
                        <span class="badge badge-dark">En proceso</span>
                    @endif
                </div>
                <div style="font-size: 20px;">
                    <label class="col-md-3 col-form-label text-md-left" for="">Folio: </label>
                    @if (!Auth::user()->folio)
                        <label for="">Se proporcionará al completar la inscripción satisfactoriamente</label>
                    @else
                        <label for="">{{ Auth::user()->folio }}</label>
                    @endif
                </div>
                <div style="font-size: 20px">
                    <label class="col-md-3 col-form-label text-md-left" for="">Nombre:</label>
                    <label for="">{{Auth::user()->nombre." ".Auth::user()->apellido_paterno." ".Auth::user()->apellido_materno}}</label>
                </div>
                <div style="font-size: 20px">
                    <label class="col-md-3 col-form-label text-md-left" for="">Correo: </label>
                    <label for="">{{Auth::user()->correo_electronico}}</label>
                </div>

                @if (Auth::user()->documentos)
                    <div style="font-size: 20px">
                        <label class="col-md-3 col-form-label text-md-left" for="">CURP:</label>
                        <label for=""> {{Auth::user()->curp}}</label>
                    </div>
                    <div style="font-size: 20px">
                        <label class="col-md-3 col-form-label text-md-left" for="">Fecha de nacimiento:</label>
                        <label for=""> {{Auth::user()->fecha_nacimiento}}</label>
                    </div>
                    <div style="font-size: 20px">
                        <label class="col-md-3 col-form-label text-md-left" for="">Escuela de origen:</label>
                        <label for=""> {{Auth::user()->escuela_origen}}</label>
                    </div>
                    <div style="font-size: 20px">
                        <label class="col-md-3 col-form-label text-md-left" for="">Teléfono:</label>
                        <label for=""> {{Auth::user()->telefono}}</label>
                    </div>
                    <div style="font-size: 20px">
                        <label class="col-md-3 col-form-label text-md-left" for="">Concurso(s):</label>
                        <label for="">
                            @foreach (Auth::user()->concursos()->get()->all() as $concurso)
                                {{ $concurso->nombre }},
                            @endforeach
                        </label>
                    </div>
                    <div style="font-size: 20px">
                        <label class="col-md-3 col-form-label text-md-left" for="">Foto para credencial:</label>
                        <img src="/storage/{{ Auth::user()->documentos->foto_asistente }}" alt="">
                    </div>
                    <br>
                    <div style="font-size: 20px">
                        <label class="col-md-3 col-form-label text-md-left" for="">Acta de nacimiento:</label>
                        <img src="/storage/{{ Auth::user()->documentos->acta_asistente }}" alt="">
                    </div>
                    <br>
                    <div style="font-size: 20px">
                        <label class="col-md-3 col-form-label text-md-left" for="">Constancia escolar:</label>
                        <img src="/storage/{{ Auth::user()->documentos->constancia_escuela }}" alt="">
                    </div>

                @endif
            </div>
        </div>

        @if (!Auth::user()->documentos)
        <div class="container justify-content-center">
            <!--
            <div class="container container-h-secundario">
                <div class="card-header h-secundario">
                    {{ __('Información necesaria para la inscripción') }}
                </div>
            </div>
        
            <div class="col-md-12 col-form-label" style="font-size: 20px">
                <p>Para solicitar tu inscripción, requieres tener a la mano la siguiente información:</p>
            </div>
            <div class="card-body" style="overflow: hidden;">
                <div style="font-size: 20px">
                    <label class="col-md-12 col-form-label text-md-center" for="">CURP, </label>
                    <label class="col-md-12 col-form-label text-md-center" for="">Fecha de nacimiento, </label>
                    <label class="col-md-12 col-form-label text-md-center" for="">Escuela de origen, </label>
                    <label class="col-md-12 col-form-label text-md-center" for="">Teléfono, </label>
                    <label class="col-md-12 col-form-label text-md-center" for="">Una foto para la credencial, </label>
                    <label class="col-md-12 col-form-label text-md-center" for="">Acta de nacimiento, </label>
                    <label class="col-md-12 col-form-label text-md-center" for="">Constancia escolar</label>
                </div>
                <br>
                
                <table class="table tabla-cursos">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" colspan="3">Escoge el o los concursos a los cuales te quieras inscribir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Concurso Regional de Física</td>
                        </tr>
                        <tr>
                            <td>Concurso Regional de Matemáticas</td>
                        </tr>
                        <tr>
                            <td>Preselectivo para la Olimpiada Nacional de Fisíca</td>
                        </tr>
                    </tbody>
                </table>
            
                <div class="col-md-12 col-form-label" style="font-size: 20px">
                    <p>Recuerda que para Inscribirte en el evento, debes enviar la documentación solicitada, la cual debe ser revisada por el Comité Organizador para proporcionarte la Inscripción correspondiente.</p>
                </div>
                <br>
            -->
            <div class="col-md-12 col-form-label" style="font-size: 20px">
                <p>Para continuar con el envío de tu solicitud deberás tener a la mano tu CURP, acta de nacimiento, una constancia de estudios expedida por la escuela donde estudias, y una fotografía para credencial, todos estos en formato JPG, JPEG o PNG.</p>
            </div>

            <div class="card-body" style="overflow: hidden;">
                <div style="font-size: 20px">
                    <a class="btn btn-primary" href="{{ route('asistentes.edit', Auth::user()->id) }}" style="float: right;" type="submit">Continuar</a>
                </div>
                <div style="font-size: 20px;">
                    <a class="btn btn-primary" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="float: left;" type="submit">Salir</a>
                </div>
            </div>
        </div>
        @else
            @if (Auth::user()->documentos->validaciones()->latest()->first()->estado == "Rechazado")
                <div class="container justify-content-center">
                    <div class="container container-h-secundario">
                        <div class="card-header h-secundario">
                            {{ __('Documentos rechazados') }}
                        </div>
                    </div>
                    <div class="card-body" style="overflow: hidden; font-size: 20px;">
                        <!--<p>A continuacion se te mostraran los archivos que se te rechazaron y su razon de rechazo.</p>-->
                        <p><b>{{ Auth::user()->documentos->validaciones()->latest()->first()->doc_rechazado }}</b></p>
                        <pre>{{ Auth::user()->documentos->validaciones()->latest()->first()->comentario }}</pre>

                        <div>Te invitamos a atender las observaciones anteriores y actualizar tus documentos para continuar con el proceso de inscripción.</div>
                        
{{--
                         <div style="font-size: 20px">
                            <label class="col-md-6 col-form-label text-md-center" for="">Foto para la credencial</label>
                            <span class="badge badge-success">Aceptado</span>
                            <span class="badge badge-danger">Rechazado</span>
                        </div>
                        <div style="font-size: 20px">
                            <label class="col-md-6 col-form-label text-md-center" for="">Acta de nacimiento</label>
                            <span class="badge badge-success">Aceptado</span>
                            <span class="badge badge-danger">Rechazado</span>
                        </div>
                        <div style="font-size: 20px">
                            <label class="col-md-6 col-form-label text-md-center" for="">Constancia de estudios</label>
                            <span class="badge badge-success">Aceptado</span>
                            <span class="badge badge-danger">Rechazado</span>
                        </div> 
    --}}
                        <div style="font-size: 20px">
                            @csrf
                            <a class="btn btn-primary" href="{{route('asistentes.rechazado')}}" style="float: right;" type="submit">Editar</a>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        
    </div>
@endsection