@extends('layouts.app_admin')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Lista de todos los alumnos que enviaron documentacion</h1>
                    <hr style="background-color: black;">
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
   {{-- @php
       dd($asistentes[1]->documentos);
   @endphp --}}
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid" >
          {{-- buscador --}}
          <label for="inputBuscadorNombre">Buscador por: Nombre, Folio, Concursos, Estado</label>
          <br>
          <input type="text" id="inputBuscadorNombre" onkeyup="buscadorPorNombre()" placeholder="Busca por nombre..." title="Escribe un nombre"> <br>
          <input type="hidden" id="vistaInscritos" name="vistaInscritos" value = 0>
          {{-- fin buscador --}}
            <div class="card" style="top: 1rem;">
                <div class="card-body table-responsive p-0" style="max-height: 700px;">
                  <table class="table table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        {{-- <th>Fecha de envio de documento</th> --}}
                        <th>No. Folio</th>
                        <th>Escuela</th> 
                        <th>Concursos</th>
                        <th>Estatus</th>
                        <th>Acción</th>
                        <th>Asesor</th>
                      </tr>
                    </thead>
                    <tbody id="listaTabla">
                      @php
                          // dd($asistentes[3]->documentos->validaciones->last());
                          // dd($asistentes[1]->documentos()->exists());
                      @endphp
                      @foreach ($asistentes as $key => $asistente)
                      
                        <tr id='tablaTR'>
                          <td><p id='nombre'>{{$asistente->nombre}} {{$asistente->apellido_paterno}} {{$asistente->apellido_materno}}</p></td>
                          {{-- @if (isset($asistente->documentos->last()->fecha_creacion)) --}}
                            {{-- <td><p >{{$asistente->documentos->fecha_creacion}}</p></td> --}}

                            @if (isset($asistente->folio))
                              <td><p id="folio">{{$asistente->folio}}</p></td>
                            @else 
                            
                              <td><p >Alumno sin número de folio</p></td>
                            @endif
                            
                            <td>
                            <p>{{ $asistente->escuela_origen ?? 'Sin escuela registrada' }}</p>
                            </td>
                            {{-- 
                              Codigo para concatenar los concursos inscrito del alumno  
                            --}}
                            @if ($asistente->documentos()->exists())
                              @php
                                  
                                $arrayConcursos = Array();
                                for($i = 0 ; $i <  count($asistente->concursos) ; $i++){
                                  $arrayConcursos[] = $asistente->concursos[$i]->nombre ;
                                  
                                }
                                $concursos = implode(", ",$arrayConcursos);
                              @endphp
                              {{-- 
                                FIN CODIGO
                              --}}
                              <td><p>{{$concursos}}</p></td>
                              @if ($asistente->documentos->validaciones->last()->estado == 'Espera')

                                <td><p>Espera</p></td>
                              @elseif($asistente->documentos->validaciones->last()->estado == 'Rechazado')
                                <td><p>Renvío</p></td>
                              @elseif($asistente->documentos->validaciones->last()->estado == 'Aprobado')
                                <td><p>Inscrito</p></td>
                              @endif
                            
                            @else
                              <td><p>Sin concursos</p></td>
                              
                              <td><p>Enviando</p></td>
                            
                            @endif

                            <td>
                              <p>{{ $asistente->asesor ? $asistente->asesor->nombre : 'Sin asesor' }}</p>
                            </td>
                            
                            <td><button value="{{$asistente->id}}" type="button" class="btn btn-info btn-sm botonInfo" data-toggle="modal" data-target="#staticBackdrop">Info</button></td>
                            {{-- @if ($asistente->documentos->last()->validaciones[$key]->estado == 'Espera' )
                              <td><a class="btn btn-primary" href="{{ route('validaciones.edit', $asistente->id) }}"  type="submit">Validar</a></td>
                            @endif --}}
                            
                          {{-- @else 
                            <td><p>Alumno no inscrito</p> </td>
                            <td><p >Alumno sin número de folio</p></td>
                          @endif --}}
                          
                          
                        </tr>
                      @endforeach
                      
                    </tbody>
                  </table>
                </div>
              </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Datos de alumno</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalBody">
        {{-- SE LLENA CON JS EN ADMIN.JS --}}
        <label for="divNombre">Nombre </label>
        <div id="divNombre"></div>
        <label for="divNacimiento" id="fNacimiento">Fecha de Nacimiento</label>
        <div id="divNacimiento"></div>
        <label for="divEscuela" id="escuela">Escuela</label>
        <div id="divEscuela"></div>
        <label for="divCorreo" id="correo">Correo electronico</label>
        <div id="divCorreo"></div>
        <label for="divTelefono" id="telefono">Telefono</label>
        <div id="divTelefono"></div>
        <label for="divCurp" id="curp">CURP</label>
        <div id="divCurp"></div>
        <label for="divFolio">Número de Folio</label>
        <div id="divFolio"></div>
        <label for="divConcursos" id="concursos">Concursos</label>
        <div id="divConcursos">
          
        </div>
        <label for="divEstatus">Estatus</label>
        <div id="divEstatus"></div>
        <label for="divImgAsistente" id="identificacion">Foto identificatoria</label>
        <div id="divImgAsistente"></div>
        <!--
        <label for="divImgAsistenteActa" id="acta">Acta de nacimiento</label>
        <div id="divImgAsistenteActa"></div>
        -->
        <label for="divImgAsistenteConstancia" id="constancia">Constancia o credencial escolar</label>
        <div id="divImgAsistenteConstancia"></div>
        {{-- FIN --}}
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar ventana</button>
        {{-- <button type="button" class="btn btn-primary">Understood</button> --}}
      </div>
    </div>
  </div>
</div>
@endsection
<script src="{{asset('js/admin.js')}}"></script>
