@extends('layouts.app_admin')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Lista de los alumnos que se le acepto la documentacion</h1>
                    <hr style="background-color: black;">
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    {{-- @php
        // dd($asistentesInscritos[0]->documentos->validaciones->last()->estado =='Aprobado');
        dd(isset($asistentesInscritos[0]->documentos->validaciones));
    @endphp --}}
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            {{-- buscador --}}
            <label for="inputBuscadorNombre">Buscador por: Nombre, Folio, Concursos, Estado</label>
            <br>
            <input type="text" id="inputBuscadorNombre" onkeyup="buscadorPorNombre()" placeholder="Busca por nombre..." title="Escribe un nombre"> <br>
            <input type="hidden" id="vistaInscritos" name="vistaInscritos" value = 1>
            {{-- fin buscador --}}
            @include('revisor.partials.indicador_en_vivo', ['vista' => 'inscritos', 'version' => $version])
            <div class="card">
                <div class="card-body table-responsive p-0" style="max-height: 700px;">
                  <table class="table table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>No. Folio</th>
                        {{-- <th>Fecha de envio de documento</th> --}}
                        <th>Concursos</th>
                        
                      </tr>
                    </thead>
                    <tbody id="listaTabla">
                      @include('revisor.partials.filas_inscritos', ['filas' => $filas])
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
        <label for="divImgAsistenteConstancia" id="constancia">Constancia escolar</label>
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

@push('scripts')
<script src="{{ asset('js/admin.js') }}?v={{ filemtime(public_path('js/admin.js')) }}"></script>
<script src="{{ asset('js/panel-tiempo-real.js') }}?v={{ filemtime(public_path('js/panel-tiempo-real.js')) }}"></script>
@endpush
