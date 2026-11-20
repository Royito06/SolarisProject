@extends('layouts.app_admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-clipboard-check mr-2"></i>Auditoría de Validaciones y Rechazos
                </h1>
                <hr style="background-color: black;">
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        {{-- Filtros --}}
        <div class="card card-outline card-secondary mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('revisores.auditoria') }}" class="form-row align-items-end">
                    <div class="col-md-4">
                        <label for="estado">Dictamen</label>
                        <select name="estado" id="estado" class="form-control">
                            <option value="">Todos (Aprobados y Rechazados)</option>
                            <option value="Aprobado" {{ request('estado') === 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                            <option value="Rechazado" {{ request('estado') === 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="id_revisor">Autor / Revisor</label>
                        <select name="id_revisor" id="id_revisor" class="form-control">
                            <option value="">Todos los revisores</option>
                            @foreach ($revisores as $rev)
                                <option value="{{ $rev->id }}" {{ request('id_revisor') == $rev->id ? 'selected' : '' }}>
                                    {{ $rev->nombre }} {{ $rev->apellido_paterno }} ({{ $rev->correo }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-filter mr-1"></i> Filtrar
                        </button>
                        <a href="{{ route('revisores.auditoria') }}" class="btn btn-secondary">Limpiar</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla de auditoría --}}
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Historial de Dictámenes ({{ $registros->count() }})</h3>
            </div>
            <div class="card-body table-responsive p-0" style="max-height: 700px;">
                <table class="table table-hover table-striped table-head-fixed mb-0">
                    <thead>
                        <tr>
                            <th>Fecha del dictamen</th>
                            <th>Acción</th>
                            <th>Autor (Revisor)</th>
                            <th>Concursante</th>
                            <th>Folio</th>
                            <th>Observaciones / Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registros as $reg)
                            @php
                                $asistente = optional($reg->documento)->asistente;
                                $revisor = $reg->revisor;
                            @endphp
                            <tr>
                                <td>{{ $reg->fecha_validacion ?? $reg->updated_at }}</td>
                                <td>
                                    @if ($reg->estado === 'Aprobado')
                                        <span class="badge badge-success">Aprobado</span>
                                    @else
                                        <span class="badge badge-danger">Rechazado</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($revisor)
                                        <strong>{{ $revisor->nombre }} {{ $revisor->apellido_paterno }}</strong><br>
                                        <small class="text-muted">{{ $revisor->correo }} ({{ $revisor->rol }})</small>
                                    @else
                                        <span class="text-muted">Sin autor registrado</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($asistente)
                                        {{ $asistente->nombre }} {{ $asistente->apellido_paterno }} {{ $asistente->apellido_materno }}<br>
                                        <small class="text-muted">{{ $asistente->curp }}</small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ optional($asistente)->folio ?? 'Sin folio' }}</td>
                                <td style="white-space: pre-line; max-width: 320px;">
                                    @if ($reg->doc_rechazado)
                                        <strong class="text-danger">Rechazado: {{ $reg->doc_rechazado }}</strong><br>
                                    @endif
                                    {{ $reg->comentario ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No hay registros de auditoría para los filtros seleccionados.
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