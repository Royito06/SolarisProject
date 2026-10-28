@extends('layouts.app_admin')

@section('content')
@php
    //dd($asistentesEspera);
@endphp
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Asistentes al concurso que estan en espera de revision</h1>
                    <hr style="background-color: black;">
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    {{-- @php
        dd(isset($asistentesEspera[0]->documentos->validaciones));
    @endphp --}}
    
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body table-responsive p-0" style="max-height: 700px;">
                  <table class="table table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Fecha de envio de documento</th>
                        <th>Validar asistente</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if ( count($asistentes) != 0)
                        @foreach ($asistentes as $asistenteEspera)
                            @if (isset($asistenteEspera->documentos->validaciones))
                                @if ($asistenteEspera->documentos->validaciones->last()->estado == 'Espera')
                                <tr>
                                  <td>{{$asistenteEspera->nombre}}</td>
                                  <td>{{$asistenteEspera->created_at}}</td>
                                  @if(Auth::guard('revisor')->user()->rol !== 'Visualizador')
                                  <td><a class="btn btn-primary" href="{{ route('validaciones.edit', $asistenteEspera->id) }}"  type="submit">Validar</a></td>
                                  @else
                                  <td><span class="badge badge-secondary"><i class="fas fa-eye mr-1"></i>Solo lectura</span></td>
                                  @endif
                                </tr>
                                @endif
                            @endif
                        @endforeach
                      @else
                        <td>No hay alumnos en espera de ser revisados</td>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
        </div>
    </div>

@endsection
