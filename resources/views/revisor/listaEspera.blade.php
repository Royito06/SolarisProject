@extends('layouts.app_admin')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark"><i class="fas fa-user-clock mr-2"></i>Asistentes al concurso en espera de revisión</h1>
                    <hr style="background-color: black;">
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            @if (session('notification'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ session('notification') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('jsAlert'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    {{ session('jsAlert') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-list mr-1"></i> Lista de Aspirantes por Evaluar
                    </h3>
                </div>
                <div class="card-body table-responsive p-0" style="max-height: 700px;">
                  <table class="table table-hover table-striped table-head-fixed text-nowrap mb-0">
                    <thead class="thead-light">
                      <tr>
                        <th>Nombre del Concursante</th>
                        <th>Fecha de Envío de Documentos</th>
                        <th style="width: 150px;">Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($asistentes as $asistenteEspera)
                        <tr>
                          <td>
                            <i class="fas fa-user text-muted mr-1"></i>
                            <strong>{{ $asistenteEspera->nombre }} {{ $asistenteEspera->apellido_paterno }} {{ $asistenteEspera->apellido_materno }}</strong>
                          </td>
                          <td>
                            {{ optional($asistenteEspera->documentos)->fecha_creacion ?? (optional($asistenteEspera->documentos)->created_at ?? $asistenteEspera->created_at) }}
                          </td>
                          <td>
                            <a class="btn btn-primary btn-sm px-3 font-weight-bold" href="{{ route('validaciones.edit', $asistenteEspera->id) }}">
                                <i class="fas fa-search-plus mr-1"></i> Validar
                            </a>
                          </td>
                        </tr>
                      @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block text-secondary"></i>
                                <h5 class="font-weight-bold text-dark">No hay alumnos pendientes de revisión en este momento</h5>
                                @if (Auth::guard('revisor')->user()->rol !== 'Visualizador')
                                    <p class="small text-muted mb-3">Puedes generar alumnos de prueba para evaluar el flujo de validación y notificaciones:</p>
                                    <a href="{{ route('revisores.generar-prueba') }}" class="btn btn-primary font-weight-bold px-4 py-2 shadow-sm">
                                        <i class="fas fa-user-plus mr-1"></i> Generar 3 Alumnos de Prueba para Evaluar
                                    </a>
                                @endif
                            </td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
        </div>
    </div>

@endsection
