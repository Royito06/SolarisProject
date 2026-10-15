@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    
                    <form method="POST" id="" action="{{route('asistentes.actualizarRechazados', Auth::user()->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <input type="hidden" name="doc" value="{{Auth::user()->documentos->id}}">
                        <div class="form-group">
                            <table class="table table-hover tabla-cursos">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" colspan="3">Selecciona los concursos a los cuales te quieres inscribir</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td>Concurso Regional de Física</td>
                                        <td><input type="checkbox" aria-label="" name="concurso[0]" value="1" {{Auth::user()->documentos->id}}></td>
                                    </tr>
                                    <tr>
                                        <td>Concurso Regional de Matemáticas</td>
                                        <td><input type="checkbox" aria-label="" name="concurso[1]" value="2" ></td>
                                    </tr>
                                    <tr>
                                        <td>Preselectivo para la Olimpiada Nacional de Fisíca</td>
                                        <td><input type="checkbox" aria-label="" name="concurso[2]" value="3" ></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @foreach ($cabecera['documentos_rechazados'] as $item)
                            @if ($item == 'Foto identificatoria')
                                <div class="form-group row">
                                    <label for="foto_asistente" class="col-md-4 col-form-label text-md-right">{{ __('Foto del Asistente') }}</label>
                                    
                                    <div class="col-md-6">
                                        {{-- <input id="foto_asistente" type="file" class="form-control" name="foto_asistente" required autocomplete="foto_asistente"> --}}
                                        <input id="foto_asistente" type="file" class="form-control @error('foto_asistente') is-invalid @enderror" name="foto_asistente" id="foto_asistente" accept="image/png, image/jpeg" required>
                                        <input type="hidden" name="image_croped" id="image_croped" value="">
                                        <span>Imagenes con terminacion JPG, JPEG y PNG.</span>
                                        @error('foto_asistente')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @elseif($item == 'Constancia escolar')
                                <div class="form-group row">
                                    <label for="constancia_escuela" class="col-md-4 col-form-label text-md-right">{{ __('Constancia de estudio o credencial escolar:') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="constancia_escuela" type="file" class="form-control @error('constancia_escuela') is-invalid @enderror" name="constancia_escuela" accept="image/png, image/jpeg" required autocomplete="constancia_escuela">
                                        <span>Imagenes con terminacion JPG, JPEG y PNG.</span>
                                        @error('constancia_escuela')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            
                        @endforeach
                        
                        <center>
                        <div class="col-md-9"><b>IMPORTANTE:</b> Recuerda que tu inscripción estará finalizada hasta que el comité así te lo confirme por correo electrónico, de modo que te pedimos estar atento al mismo.</div>
                        </center><br>
<!--HABILITAR/DESHABILITAR para los registros de asistentes    -->                    
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enviar solicitud de inscripción') }}
                                </button>
                            </div>
                        </div>
                    <!--hasta aqui-->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div id="uploadimageModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
                <h4 class="modal-title">Recorta tu imagen</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 text-center">
                        <div id="image_demo" style="width:350px; margin-top:30px"></div>
                    </div>
                    <div class="col-md-4" style="padding-top:30px;">
                        <br />
                        <br />
                        <br/>
                        <button class="btn btn-success crop_image">Recortar imagen</button>
                    </div>
                </div>
            </div>
{{--             <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> --}}
        </div>
    </div>
</div>

@endsection