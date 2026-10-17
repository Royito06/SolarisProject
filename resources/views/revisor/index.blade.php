@extends('layouts.app_admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-users-cog mr-2"></i>Gestión y Carga de Revisores</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('revisores.index') }}">Inicio</a></li>
                        <li class="breadcrumb-item active">Revisores</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            {{-- Alertas de éxito o error --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-check-circle mr-1"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-exclamation-triangle mr-1"></i>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-ban mr-1"></i>
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                {{-- Formulario de carga masiva y manual --}}
                <div class="col-lg-12">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-user-plus mr-1"></i> Registrar Revisores (Archivo o Manual)
                            </h3>
                        </div>
                        <form action="{{ route('revisores.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    {{-- Opción 1: Archivo .txt o .csv --}}
                                    <div class="col-md-6 border-right">
                                        <h5 class="text-primary"><i class="fas fa-file-alt mr-1"></i> Opción A: Cargar archivo (.txt / .csv)</h5>
                                        <p class="text-muted small">
                                            Sube un archivo de texto (<code>.txt</code>) o un archivo separado por comas (<code>.csv</code>) con la lista de correos electrónicos.
                                        </p>
                                        <div class="form-group">
                                            <label for="archivo_correos">Seleccionar archivo:</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="archivo_correos" name="archivo_correos" accept=".txt,.csv">
                                                <label class="custom-file-label" for="archivo_correos">Elegir archivo .txt o .csv...</label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Los correos en el archivo pueden estar separados por comas, punto y coma o uno por renglón.
                                            </small>
                                        </div>
                                    </div>

                                    {{-- Opción 2: Entrada Manual --}}
                                    <div class="col-md-6">
                                        <h5 class="text-primary"><i class="fas fa-keyboard mr-1"></i> Opción B: Ingresar correo(s) manualmente</h5>
                                        <p class="text-muted small">
                                            Escribe un correo individual o pega varios correos separados por comas o saltos de línea.
                                        </p>
                                        <div class="form-group">
                                            <label for="correos_manual">Correos electrónicos:</label>
                                            <textarea class="form-control" id="correos_manual" name="correos_manual" rows="3" placeholder="ejemplo@unison.mx, revisor2@unison.mx"></textarea>
                                            <small class="form-text text-muted">
                                                Puedes escribir un único correo o varios separados por comas.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                {{-- Selector de rol y contraseña --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="rol" class="font-weight-bold">
                                                <i class="fas fa-id-badge mr-1"></i> Nivel de Permisos / Rol a Asignar:
                                            </label>
                                            <select name="rol" id="rol" class="form-control" required>
                                                <option value="Revisor" selected>Revisor (Evaluación y dictamen de aspirantes)</option>
                                                <option value="Visualizador">Visualizador (Solo consulta de información)</option>
                                                <option value="Administrador">Administrador (Control total del sistema)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-info mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            <strong>Nota de seguridad:</strong> A cada revisor registrado se le creará su cuenta con la contraseña temporal: <code>Concurso2026!</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white text-right">
                                <button type="submit" class="btn btn-primary px-4 font-weight-bold">
                                    <i class="fas fa-save mr-1"></i> Guardar y Registrar Revisores
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Tabla con los revisores actuales --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-secondary card-outline shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-list mr-1"></i> Revisores y Administradores Registrados
                            </h3>
                            <div class="card-tools ml-auto">
                                <span class="badge badge-primary px-2 py-1">
                                    Total: {{ isset($revisores) ? count($revisores) : 0 }} usuario(s)
                                </span>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-striped text-nowrap mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 60px;"># ID</th>
                                        <th>Nombre Completo</th>
                                        <th>Correo Electrónico</th>
                                        <th>Rol / Permiso</th>
                                        <th>Fecha de Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($revisores as $revisor)
                                        <tr>
                                            <td><strong>#{{ $revisor->id }}</strong></td>
                                            <td>
                                                <i class="fas fa-user-circle text-muted mr-1"></i>
                                                {{ $revisor->nombre }} {{ $revisor->apellido_paterno }} {{ $revisor->apellido_materno }}
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $revisor->correo }}">{{ $revisor->correo }}</a>
                                            </td>
                                            <td>
                                                @if ($revisor->rol === 'Administrador')
                                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-shield-alt mr-1"></i>Administrador</span>
                                                @elseif ($revisor->rol === 'Visualizador')
                                                    <span class="badge badge-info px-2 py-1"><i class="fas fa-eye mr-1"></i>Visualizador</span>
                                                @else
                                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>Revisor</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $revisor->created_at ? $revisor->created_at->format('d/m/Y H:i') : 'Sin fecha' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                                                Aún no hay revisores dados de alta en el sistema.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <script>
        // Actualizar el nombre del archivo seleccionado en el label del input
        document.addEventListener('DOMContentLoaded', function () {
            var fileInput = document.getElementById('archivo_correos');
            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    var fileName = e.target.files[0] ? e.target.files[0].name : 'Elegir archivo .txt o .csv...';
                    var label = document.querySelector('.custom-file-label');
                    if (label) {
                        label.innerText = fileName;
                    }
                });
            }
        });
    </script>
@endsection