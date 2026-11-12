@extends('layouts.app_admin')

@section('content')
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

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            @if (session('warning'))
                <div class="alert alert-warning" role="alert">{{ session('warning') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif

            @include('revisor.partials.indicador_en_vivo', ['vista' => 'espera', 'version' => $version])
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
                    <tbody id="listaTabla">
                      @include('revisor.partials.filas_espera', ['filas' => $filas])
                    </tbody>
                  </table>
                </div>
              </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin.js') }}?v={{ filemtime(public_path('js/admin.js')) }}"></script>
<script src="{{ asset('js/panel-tiempo-real.js') }}?v={{ filemtime(public_path('js/panel-tiempo-real.js')) }}"></script>
@endpush
