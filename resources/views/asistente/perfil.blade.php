@extends('layouts.app')

@section('content')
@php
    $validacionActual = Auth::user()->documentos ? Auth::user()->documentos->validaciones()->latest()->first() : null;
@endphp
    <div class="container">
        <div class="justify-content-center">

            {{-- Notificación visual de estatus en el perfil --}}
            @if ($validacionActual)
                @if ($validacionActual->estado == "Aprobado")
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <h4 class="alert-heading font-weight-bold">
                            <i class="fas fa-check-circle mr-2"></i>¡Felicidades! Tu inscripción ha sido APROBADA
                        </h4>
                        <p class="mb-1">
                            El Comité Evaluador ha validado tu documentación. Tu número de folio oficial para el concurso es: 
                            <strong class="badge badge-success" style="font-size: 1.1em; letter-spacing: 1px;">{{ Auth::user()->folio }}</strong>
                        </p>
                        <hr>
                        <p class="mb-0 small">
                            Se ha enviado un correo electrónico de confirmación a <strong>{{ Auth::user()->correo_electronico }}</strong> con las instrucciones para el día del evento.
                        </p>
                    </div>
                @elseif ($validacionActual->estado == "Rechazado")
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <h4 class="alert-heading font-weight-bold">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Tu solicitud requiere correcciones
                        </h4>
                        <p class="mb-1">
                            El Comité Evaluador revisó tus documentos y encontró observaciones que debes corregir para poder participar.
                        </p>
                        <hr>
                        <p class="mb-0 small">
                            Se ha enviado una notificación detallada a tu correo <strong>{{ Auth::user()->correo_electronico }}</strong>. También puedes consultar los detalles abajo y reenviar tus archivos.
                        </p>
                    </div>
                @elseif ($validacionActual->estado == "Espera")
                    <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                        <h4 class="alert-heading font-weight-bold">
                            <i class="fas fa-clock mr-2"></i>Documentos en revisión
                        </h4>
                        <p class="mb-0">
                            Tus documentos han sido recibidos y se encuentran en lista de espera para ser evaluados por los revisores del concurso.
                        </p>
                    </div>
                @endif
            @endif

            <div class="card-body" style="overflow: hidden;">
                <div style="font-size: 20px">
                    <label for=""><b>Estatus de la solicitud de inscripción: </b></label>
                    @if (Auth::user()->documentos)
                        @if ($validacionActual && $validacionActual->estado == "Rechazado")
                            <span class="badge badge-danger">Rechazado con observaciones</span>
                        @elseif ($validacionActual && $validacionActual->estado == "Espera")
                            <span class="badge badge-secondary">En revisión</span>
                        @elseif ($validacionActual && $validacionActual->estado == "Aprobado")
                            <span class="badge badge-success">Inscrito</span>
                        @endif
                    @else
                        <span class="badge badge-dark">En proceso de registro</span>
                    @endif
                </div>

                <div style="font-size: 20px;">
                    <label class="col-md-3 col-form-label text-md-left" for="">Folio: </label>
                    @if (!Auth::user()->folio)
                        <label for="" class="text-muted">Se proporcionará al completar la inscripción satisfactoriamente</label>
                    @else
                        <label for=""><strong style="color: #004990;">{{ Auth::user()->folio }}</strong></label>
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
                                <span class="badge badge-light border">{{ $concurso->nombre }}</span>
                            @endforeach
                        </label>
                    </div>
                    <div style="font-size: 20px">
                        <label class="col-md-3 col-form-label text-md-left" for="">Foto para credencial:</label>
                        <img src="/storage/{{ Auth::user()->documentos->foto_asistente }}" alt="" style="max-height: 200px; border-radius: 4px; border: 1px solid #ddd;">
                    </div>
                    <br>
                    <div style="font-size: 20px">
                        <label class="col-md-3 col-form-label text-md-left" for="">Constancia escolar:</label>
                        <img src="/storage/{{ Auth::user()->documentos->constancia_escuela }}" alt="" style="max-height: 200px; border-radius: 4px; border: 1px solid #ddd;">
                    </div>

                @endif
            </div>
        </div>

        @if (!Auth::user()->documentos)
        <div class="container justify-content-center">
            <div class="col-md-12 col-form-label" style="font-size: 20px">
                <p>Para continuar con el envío de tu solicitud deberás tener a la mano tu CURP, una constancia de estudios (o tu credencial de la escuela) expedida por la escuela donde estudias, y una fotografía para credencial, todos estos en formato JPG, JPEG o PNG.</p>
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
            @if ($validacionActual && $validacionActual->estado == "Rechazado")
                <div class="container justify-content-center mt-3">
                    <div class="card card-danger card-outline shadow-sm">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title mb-0 font-weight-bold">
                                <i class="fas fa-exclamation-circle mr-1"></i> Observaciones del Comité Evaluador
                            </h5>
                        </div>
                        <div class="card-body" style="font-size: 18px;">
                            <p><strong>Documento(s) observados:</strong> <span class="badge badge-warning text-dark p-2">{{ $validacionActual->doc_rechazado }}</span></p>
                            <div class="bg-light p-3 border rounded mb-3">
                                <pre style="margin: 0; font-family: inherit; font-size: 16px; white-space: pre-wrap;">{{ $validacionActual->comentario }}</pre>
                            </div>

                            <p class="text-muted">Te invitamos a atender las observaciones anteriores y actualizar tus documentos para continuar con el proceso de inscripción.</p>
                            
                            <div class="text-right">
                                @csrf
                                <a class="btn btn-warning font-weight-bold px-4" href="{{route('asistentes.rechazado')}}" type="submit">
                                    <i class="fas fa-edit mr-1"></i> Corregir y Reenviar Documentos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        
    </div>
@endsection
