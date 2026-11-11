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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

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
                      @php $hayEnEspera = false; @endphp
                      @foreach ($asistentes as $asistenteEspera)
                          @if (isset($asistenteEspera->documentos->validaciones) && $asistenteEspera->documentos->validaciones->isNotEmpty())
                              @if ($asistenteEspera->documentos->validaciones->last()->estado == 'Espera')
                              @php $hayEnEspera = true; @endphp
                              <tr>
                                <td>{{$asistenteEspera->nombre}} {{$asistenteEspera->apellido_paterno}} {{$asistenteEspera->apellido_materno}}</td>
                                <td>{{$asistenteEspera->created_at}}</td>
                                @if((Auth::guard('revisor')->user()->rol ?? '') !== 'Visualizador')
                                <td><a class="btn btn-primary" href="{{ route('validaciones.edit', $asistenteEspera->id) }}"  type="submit">Validar</a></td>
                                @else
                                <td><span class="badge badge-secondary"><i class="fas fa-eye mr-1"></i>Solo lectura</span></td>
                                @endif
                              </tr>
                              @endif
                          @endif
                      @endforeach

                      @if(!$hayEnEspera)
                        <tr>
                          <td colspan="3" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle mr-1"></i> No hay alumnos en espera de ser revisados
                          </td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
        </div>
    </div>

@endsection
